# College Database - Technical Documentation

## Terminology / Terminoloogia

This document uses the following terminology consistently:

| English Term | Estonian Term | Database Table | Notes |
|--------------|---------------|----------------|-------|
| Department | Osakond/Kolledž | `departments` | College/school organizational unit |
| Instructor | Õpetaja/Õppejõud | `instructors` | Teacher/faculty member |
| Department Head | Osakonnajuhataja | `department_heads` | Department leader/manager |
| Course | Kursus/Õppeaine | `courses` | Subject/class offering |
| Student | Õpilane | `students` | Enrolled learner |
| Enrollment | Registreerimine | `enrollments` | Student-course registration with grade |

**Note**: The terms "College" and "Department" are used interchangeably in requirements, but in database implementation we use "Department" consistently for the organizational unit (table: `departments`).

## Database Schema Overview

This document provides detailed technical documentation for the College Database Management System.

## Entity-Relationship Diagram (ERD) Description

### Entities and Relationships

```
Departments (1) ←→ (N) Instructors
Departments (1) ←→ (1) DepartmentHeads
Departments (1) ←→ (N) Courses
Departments (1) ←→ (N) Students (major_department)
Instructors (1) ←→ (N) Courses
Instructors (1) ←→ (1) DepartmentHeads
Students (N) ←→ (N) Courses (through Enrollments)
```

## Table Specifications

### 1. Departments Table

**Purpose**: Store information about college departments/schools

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| department_id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique department identifier |
| department_name | VARCHAR(100) | NOT NULL, UNIQUE | Department name |
| building | VARCHAR(100) | NULL | Building location |
| budget | DECIMAL(12,2) | NULL | Department budget |
| created_at | TIMESTAMP | NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NULL | Record update timestamp |

**Relationships**:
- Has many Instructors
- Has many Courses
- Has many Students (as major department)
- Has one DepartmentHead

### 2. Instructors Table

**Purpose**: Store instructor/teacher information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| instructor_id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique instructor identifier |
| first_name | VARCHAR(50) | NOT NULL | First name |
| last_name | VARCHAR(50) | NOT NULL | Last name |
| email | VARCHAR(100) | NOT NULL, UNIQUE | Email address |
| phone | VARCHAR(20) | NULL | Phone number |
| department_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Department affiliation |
| salary | DECIMAL(10,2) | NULL | Salary information |
| hire_date | DATE | NULL | Date of hire |
| created_at | TIMESTAMP | NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NULL | Record update timestamp |

**Relationships**:
- Belongs to one Department
- Has many Courses
- May be one DepartmentHead

**Foreign Keys**:
- department_id → departments(department_id) ON DELETE RESTRICT

### 3. DepartmentHeads Table

**Purpose**: Define department leadership (one-to-one relationship)

**IMPORTANT - NO HISTORICAL TRACKING**: This table stores ONLY the current department head. When leadership changes, you must either:
1. Update the existing record (change instructor_id and start_date), OR  
2. Delete the old record and insert a new one

If you need to track historical department heads (who was head before, when they served, etc.), this table structure would need to be modified to include an `end_date` field and remove the primary key constraint on `department_id`.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| department_id | BIGINT UNSIGNED | PRIMARY KEY, FOREIGN KEY | Department identifier |
| instructor_id | BIGINT UNSIGNED | NOT NULL, UNIQUE, FOREIGN KEY | Head instructor identifier |
| start_date | DATE | NOT NULL | Start date as department head |
| created_at | TIMESTAMP | NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NULL | Record update timestamp |

**Relationships**:
- Belongs to one Department
- Belongs to one Instructor

**Foreign Keys**:
- department_id → departments(department_id) ON DELETE CASCADE
- instructor_id → instructors(instructor_id) ON DELETE CASCADE

**Business Rules**:
- One department can have at most one head at a time (enforced by PRIMARY KEY on department_id)
- One instructor can head only one department (enforced by UNIQUE constraint on instructor_id)
- A department can exist without a head (this is an optional relationship)
- When a department or instructor is deleted, the head record is automatically removed (CASCADE delete)

### 4. Courses Table

**Purpose**: Store course/subject information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| course_id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique course identifier |
| course_code | VARCHAR(20) | NOT NULL, UNIQUE | Course code (e.g., CS101, MATH200) |
| course_name | VARCHAR(100) | NOT NULL | Full course name (e.g., "Introduction to Programming") |
| department_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Offering department |
| instructor_id | BIGINT UNSIGNED | NULL, FOREIGN KEY | Teaching instructor |
| credits | INT | NOT NULL, DEFAULT 3 | Credit points (typically 1-6) |
| semester | VARCHAR(20) | NULL | Semester name (e.g., "Autumn 2024", "Spring 2025", "Fall 2024") - flexible text format |
| year | INT | NULL | Academic year (e.g., 2024, 2025) - used with semester for scheduling |
| room_number | VARCHAR(20) | NULL | Physical classroom location (e.g., "A-101", "B-404", "Lab 3", "Online") |
| schedule | VARCHAR(100) | NULL | Weekly class times (e.g., "Mon/Wed 10:00-11:30", "Tue/Thu 14:00-16:00") - free text |
| created_at | TIMESTAMP | NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NULL | Record update timestamp |

**Field Details**:
- **semester**: Free-text field to accommodate different semester naming conventions (Autumn/Spring, Fall/Winter, etc.)
- **year**: Academic year when the course runs, used together with semester
- **room_number**: Where the course physically meets (building-room format or "Online" for remote courses)
- **schedule**: Weekly meeting pattern in free-text format for flexibility

**Relationships**:
- Belongs to one Department
- Belongs to one Instructor
- Has many Students (through Enrollments)

**Foreign Keys**:
- department_id → departments(department_id) ON DELETE RESTRICT
- instructor_id → instructors(instructor_id) ON DELETE SET NULL

### 5. Students Table

**Purpose**: Store student information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| student_id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique student identifier |
| first_name | VARCHAR(50) | NOT NULL | First name |
| last_name | VARCHAR(50) | NOT NULL | Last name |
| email | VARCHAR(100) | NOT NULL, UNIQUE | Email address |
| phone | VARCHAR(20) | NULL | Phone number |
| date_of_birth | DATE | NULL | Date of birth |
| enrollment_year | INT | NULL | Year of enrollment |
| major_department_id | BIGINT UNSIGNED | NULL, FOREIGN KEY | Major department |
| gpa | DECIMAL(3,2) | NULL | Grade Point Average (0.00-5.00) |
| created_at | TIMESTAMP | NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NULL | Record update timestamp |

**Relationships**:
- Belongs to one Department (major)
- Has many Courses (through Enrollments)

**Foreign Keys**:
- major_department_id → departments(department_id) ON DELETE SET NULL

### 6. Enrollments Table

**Purpose**: Many-to-many relationship between Students and Courses, includes grades

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| enrollment_id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique enrollment identifier |
| student_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Enrolled student |
| course_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Enrolled course |
| enrollment_date | DATE | NOT NULL | Date of enrollment |
| grade | VARCHAR(2) | NULL | Final grade (A, MA, 1-5) |
| status | VARCHAR(20) | NOT NULL, DEFAULT 'Active' | Enrollment status |
| created_at | TIMESTAMP | NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NULL | Record update timestamp |

**Relationships**:
- Belongs to one Student
- Belongs to one Course

**Foreign Keys**:
- student_id → students(student_id) ON DELETE CASCADE
- course_id → courses(course_id) ON DELETE CASCADE

**Constraints**:
- UNIQUE(student_id, course_id) - Prevents duplicate enrollments

**Status Values**:
- Active - Currently enrolled
- Completed - Course finished with grade
- Dropped - Student dropped the course
- Failed - Student failed the course

## Sample Queries

### 1. Get all courses taught by a specific instructor
```sql
SELECT c.course_code, c.course_name, c.semester, c.room_number
FROM courses c
INNER JOIN instructors i ON c.instructor_id = i.instructor_id
WHERE i.email = 'john.smith@college.edu';
```

### 2. Get all students enrolled in a specific course
```sql
SELECT s.first_name, s.last_name, s.email, e.grade, e.status
FROM students s
INNER JOIN enrollments e ON s.student_id = e.student_id
INNER JOIN courses c ON e.course_id = c.course_id
WHERE c.course_code = 'CS101';
```

### 3. Get department head information
```sql
SELECT d.department_name, i.first_name, i.last_name, dh.start_date
FROM departments d
INNER JOIN department_heads dh ON d.department_id = dh.department_id
INNER JOIN instructors i ON dh.instructor_id = i.instructor_id;
```

### 4. Get student's current courses
```sql
SELECT c.course_code, c.course_name, i.last_name as instructor, e.status
FROM enrollments e
INNER JOIN courses c ON e.course_id = c.course_id
INNER JOIN instructors i ON c.instructor_id = i.instructor_id
INNER JOIN students s ON e.student_id = s.student_id
WHERE s.email = 'anna.kask@student.college.edu'
AND e.status = 'Active';
```

### 5. Get all instructors in a department with their courses
```sql
SELECT i.first_name, i.last_name, c.course_code, c.course_name
FROM instructors i
LEFT JOIN courses c ON i.instructor_id = c.instructor_id
WHERE i.department_id = 1
ORDER BY i.last_name, c.course_code;
```

## Data Integrity Rules

### Referential Integrity

1. **Instructors must belong to a department** (department_id NOT NULL)
2. **Courses must belong to a department** (department_id NOT NULL)
3. **Enrollments require both student and course** (both NOT NULL)
4. **Department heads must be valid instructors** (foreign key constraint)

### Deletion Rules

1. **Deleting a Department**: 
   - RESTRICTED if instructors or courses exist
   - CASCADE deletes department head record

2. **Deleting an Instructor**:
   - RESTRICTED if department requires them
   - SET NULL for courses they teach
   - CASCADE deletes department head record

3. **Deleting a Course**:
   - CASCADE deletes all enrollments

4. **Deleting a Student**:
   - CASCADE deletes all enrollments

### Unique Constraints

1. Department names must be unique
2. Instructor emails must be unique
3. Student emails must be unique
4. Course codes must be unique
5. One student can enroll in a course only once
6. One instructor can head only one department

## Performance Considerations

### Indexes

The schema includes indexes on:
- Foreign key columns (automatic in most databases)
- Email fields (for quick lookup)
- Course codes (for quick search)
- Enrollment status (for filtering)

### Optimization Tips

1. Use appropriate JOIN types based on requirements
2. Index frequently queried columns
3. Use LIMIT when paginating results
4. Consider caching for frequently accessed data
5. Monitor slow queries and add indexes as needed

## Security Considerations

### Data Integrity
1. **Email Uniqueness**: Prevents duplicate accounts
2. **Cascade Deletes**: Automatically cleans up related records
3. **Foreign Key Constraints**: Maintains data integrity
4. **NULL Handling**: Allows for flexible data entry while maintaining required fields

### Database User Permissions

**IMPORTANT SECURITY NOTE**: The PHP application user should have LIMITED database privileges.

**Recommended Permissions** (for production environment):
```sql
-- Create a dedicated database user for the application
CREATE USER 'college_app'@'localhost' IDENTIFIED BY 'secure_password';

-- Grant only necessary privileges (NO DROP TABLE rights)
GRANT SELECT, INSERT, UPDATE, DELETE ON college_db.* TO 'college_app'@'localhost';

-- DO NOT grant these privileges to application user:
-- DROP, CREATE, ALTER, INDEX, GRANT OPTION
```

**WHY**: 
- Application user should NOT be able to drop tables or alter schema
- Schema changes should only be done through migrations by administrators
- This prevents accidental or malicious destruction of database structure
- Limits damage from SQL injection attacks

**DO NOT USE** in production:
```php
// ❌ NEVER do this in production code:
define('DB_USER', 'root'); 
```

**Example .env configuration** (production):
```env
DB_USERNAME=college_app  # Limited privilege user
DB_PASSWORD=secure_random_password_here
```

**For development only**, you may use root or a user with full privileges, but ensure this is NEVER deployed to production.

## Migration Order

When creating the database, tables must be created in this order to satisfy foreign key dependencies:

1. departments
2. instructors
3. department_heads
4. courses
5. students
6. enrollments

When dropping tables, reverse the order:

1. enrollments
2. students
3. courses
4. department_heads
5. instructors
6. departments
