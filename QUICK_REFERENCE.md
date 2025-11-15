# Quick Reference Guide

## Common Database Operations

### Setup Commands

```bash
# 1. Install dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Configure .env file with your database credentials
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Generate application key
php artisan key:generate

# 5. Run migrations to create tables
php artisan migrate

# 6. Seed database with sample data
php artisan db:seed

# 7. Validate setup
./validate.sh
```

### Migration Commands

```bash
# Run all pending migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Rollback and re-run all migrations
php artisan migrate:refresh

# Rollback, re-run, and seed
php artisan migrate:refresh --seed

# Check migration status
php artisan migrate:status
```

### Database Commands

```bash
# Seed database
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=DatabaseSeeder

# Wipe database (drop all tables)
php artisan db:wipe
```

### Tinker Commands (Interactive PHP Shell)

```bash
# Start tinker
php artisan tinker

# Example queries in tinker:
# Get all departments
App\Models\Department::all()

# Find specific department
App\Models\Department::find(1)

# Get department with instructors
App\Models\Department::with('instructors')->find(1)

# Create new student
App\Models\Student::create([
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test@student.college.edu',
    'major_department_id' => 1,
    'enrollment_year' => 2024
])

# Get student's courses
App\Models\Student::find(1)->courses

# Get course enrollments
App\Models\Course::find(1)->enrollments
```

## Common SQL Queries

### Department Queries

```sql
-- List all departments with their budgets
SELECT department_name, building, budget 
FROM departments 
ORDER BY department_name;

-- Get department with most instructors
SELECT d.department_name, COUNT(i.instructor_id) as instructor_count
FROM departments d
LEFT JOIN instructors i ON d.department_id = i.department_id
GROUP BY d.department_id, d.department_name
ORDER BY instructor_count DESC;
```

### Instructor Queries

```sql
-- List all instructors with their departments
SELECT i.first_name, i.last_name, i.email, d.department_name
FROM instructors i
INNER JOIN departments d ON i.department_id = d.department_id
ORDER BY d.department_name, i.last_name;

-- Find department heads
SELECT d.department_name, i.first_name, i.last_name, dh.start_date
FROM department_heads dh
INNER JOIN departments d ON dh.department_id = d.department_id
INNER JOIN instructors i ON dh.instructor_id = i.instructor_id;

-- Get instructors teaching specific courses
SELECT DISTINCT i.first_name, i.last_name, c.course_code, c.course_name
FROM instructors i
INNER JOIN courses c ON i.instructor_id = c.instructor_id
ORDER BY i.last_name;
```

### Course Queries

```sql
-- List all courses with instructor names
SELECT c.course_code, c.course_name, c.credits, 
       CONCAT(i.first_name, ' ', i.last_name) as instructor
FROM courses c
LEFT JOIN instructors i ON c.instructor_id = i.instructor_id
ORDER BY c.course_code;

-- Get courses by department
SELECT d.department_name, c.course_code, c.course_name, c.semester
FROM courses c
INNER JOIN departments d ON c.department_id = d.department_id
WHERE d.department_id = 1
ORDER BY c.course_code;

-- Find courses with no enrollments
SELECT c.course_code, c.course_name
FROM courses c
LEFT JOIN enrollments e ON c.course_id = e.course_id
WHERE e.enrollment_id IS NULL;
```

### Student Queries

```sql
-- List all students with their majors
SELECT s.first_name, s.last_name, s.email, d.department_name as major, s.gpa
FROM students s
LEFT JOIN departments d ON s.major_department_id = d.department_id
ORDER BY s.last_name;

-- Get students by GPA range
SELECT first_name, last_name, gpa
FROM students
WHERE gpa >= 4.0
ORDER BY gpa DESC;

-- Find students enrolled in specific course
SELECT s.first_name, s.last_name, s.email, e.grade, e.status
FROM students s
INNER JOIN enrollments e ON s.student_id = e.student_id
INNER JOIN courses c ON e.course_id = c.course_id
WHERE c.course_code = 'CS101';
```

### Enrollment Queries

```sql
-- Get all enrollments with student and course info
SELECT s.first_name, s.last_name, c.course_code, c.course_name, 
       e.enrollment_date, e.grade, e.status
FROM enrollments e
INNER JOIN students s ON e.student_id = s.student_id
INNER JOIN courses c ON e.course_id = c.course_id
ORDER BY e.enrollment_date DESC;

-- Get student's grade report
SELECT c.course_code, c.course_name, c.credits, e.grade, e.status
FROM enrollments e
INNER JOIN courses c ON e.course_id = c.course_id
WHERE e.student_id = 1
ORDER BY c.semester, c.course_code;

-- Count enrollments by status
SELECT status, COUNT(*) as count
FROM enrollments
GROUP BY status;

-- Get completed courses with grades
SELECT s.first_name, s.last_name, c.course_code, e.grade
FROM enrollments e
INNER JOIN students s ON e.student_id = s.student_id
INNER JOIN courses c ON e.course_id = c.course_id
WHERE e.status = 'Completed'
ORDER BY s.last_name, c.course_code;
```

## Eloquent Examples

### Creating Records

```php
// Create a new department
$department = Department::create([
    'department_name' => 'Physics',
    'building' => 'Building D',
    'budget' => 400000.00
]);

// Create an instructor
$instructor = Instructor::create([
    'first_name' => 'Jane',
    'last_name' => 'Doe',
    'email' => 'jane.doe@college.edu',
    'phone' => '+372-555-9999',
    'department_id' => 1,
    'salary' => 65000.00,
    'hire_date' => '2024-01-15'
]);

// Enroll a student in a course
Enrollment::create([
    'student_id' => 1,
    'course_id' => 2,
    'enrollment_date' => now(),
    'status' => 'Active'
]);
```

### Querying with Relationships

```php
// Get department with all its instructors
$department = Department::with('instructors')->find(1);

// Get student with all enrolled courses
$student = Student::with('courses')->find(1);

// Get course with all enrolled students
$course = Course::with('students')->find(1);

// Get instructor with department and courses
$instructor = Instructor::with(['department', 'courses'])->find(1);

// Get department head information
$department = Department::with('departmentHead.instructor')->find(1);
```

### Updating Records

```php
// Update student GPA
$student = Student::find(1);
$student->gpa = 4.50;
$student->save();

// Update enrollment grade
$enrollment = Enrollment::where('student_id', 1)
                       ->where('course_id', 1)
                       ->first();
$enrollment->grade = '5';
$enrollment->status = 'Completed';
$enrollment->save();

// Update instructor salary
Instructor::where('instructor_id', 1)
          ->update(['salary' => 70000.00]);
```

### Deleting Records

```php
// Delete a course (cascades to enrollments)
Course::find(1)->delete();

// Delete an enrollment
Enrollment::where('student_id', 1)
          ->where('course_id', 2)
          ->delete();
```

## Useful Validation Rules

When adding forms/API endpoints, consider these validation rules:

```php
// Department validation
'department_name' => 'required|string|max:100|unique:departments',
'building' => 'nullable|string|max:100',
'budget' => 'nullable|numeric|min:0',

// Instructor validation
'first_name' => 'required|string|max:50',
'last_name' => 'required|string|max:50',
'email' => 'required|email|unique:instructors',
'department_id' => 'required|exists:departments,department_id',
'salary' => 'nullable|numeric|min:0',

// Student validation
'first_name' => 'required|string|max:50',
'last_name' => 'required|string|max:50',
'email' => 'required|email|unique:students',
'gpa' => 'nullable|numeric|between:0,5',
'enrollment_year' => 'nullable|integer|min:1900|max:' . date('Y'),

// Course validation
'course_code' => 'required|string|max:20|unique:courses',
'course_name' => 'required|string|max:100',
'department_id' => 'required|exists:departments,department_id',
'credits' => 'required|integer|min:1|max:10',

// Enrollment validation
'student_id' => 'required|exists:students,student_id',
'course_id' => 'required|exists:courses,course_id',
'enrollment_date' => 'required|date',
'grade' => 'nullable|in:1,2,3,4,5,A,MA',
'status' => 'required|in:Active,Completed,Dropped,Failed',
```

## Troubleshooting

### Common Issues

**Migration fails with "Class not found"**
```bash
composer dump-autoload
php artisan migrate
```

**Foreign key constraint fails**
- Check that parent records exist before creating child records
- Verify migration order is correct

**Duplicate entry error**
- Check unique constraints (emails, course codes, department names)
- Verify student isn't already enrolled in the course

**Connection refused**
- Verify database credentials in .env
- Ensure MySQL service is running
- Check database exists: `CREATE DATABASE college_db;`

## Performance Tips

1. **Use eager loading** to avoid N+1 queries:
   ```php
   // Bad
   $students = Student::all();
   foreach ($students as $student) {
       echo $student->majorDepartment->department_name;
   }
   
   // Good
   $students = Student::with('majorDepartment')->get();
   ```

2. **Use select() to limit columns**:
   ```php
   Student::select('student_id', 'first_name', 'last_name')->get();
   ```

3. **Use pagination for large datasets**:
   ```php
   Student::paginate(20);
   ```

4. **Index frequently queried columns** (already done in migrations)
