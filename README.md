# College Database Management System

A comprehensive Laravel-based college database management system with MySQL backend.

## Overview

This project implements a complete college database system for managing departments, instructors, courses, students, and enrollments. The system is built with PHP Laravel framework and follows proper database normalization principles.

## Database Structure

### Tables

#### 1. Departments (Osakonnad/Koolid)
- **Primary Key**: `department_id`
- **Fields**:
  - `department_name` (VARCHAR 100, UNIQUE, REQUIRED) - Department name
  - `building` (VARCHAR 100) - Building location
  - `budget` (DECIMAL 12,2) - Department budget
  - `created_at`, `updated_at` - Automatic timestamps

#### 2. Instructors (Õpetajad/Õppejõud)
- **Primary Key**: `instructor_id`
- **Foreign Keys**: `department_id` → Departments
- **Fields**:
  - `first_name`, `last_name` (VARCHAR 50, REQUIRED) - Name
  - `email` (VARCHAR 100, UNIQUE, REQUIRED) - Email address
  - `phone` (VARCHAR 20) - Phone number
  - `department_id` (INT, REQUIRED) - Department affiliation
  - `salary` (DECIMAL 10,2) - Salary information
  - `hire_date` (DATE) - Date of hire
  - `created_at`, `updated_at` - Automatic timestamps

#### 3. DepartmentHeads (Osakonnajuhatajad)
- **Primary Key**: `department_id`
- **Foreign Keys**: 
  - `department_id` → Departments
  - `instructor_id` (UNIQUE) → Instructors
- **Fields**:
  - `instructor_id` (INT, UNIQUE, REQUIRED) - Head instructor
  - `start_date` (DATE, REQUIRED) - Start date as head
  - `created_at`, `updated_at` - Automatic timestamps
- **Rules**: 
  - One department can have at most one head
  - One instructor can head only one department

#### 4. Courses (Kursused/Õppeained)
- **Primary Key**: `course_id`
- **Foreign Keys**: 
  - `department_id` → Departments
  - `instructor_id` → Instructors
- **Fields**:
  - `course_code` (VARCHAR 20, UNIQUE, REQUIRED) - Course code (e.g., CS101)
  - `course_name` (VARCHAR 100, REQUIRED) - Course name
  - `department_id` (INT, REQUIRED) - Department offering the course
  - `instructor_id` (INT) - Teaching instructor
  - `credits` (INT, DEFAULT 3) - Credit points
  - `semester` (VARCHAR 20) - Semester (e.g., "Autumn 2024")
  - `year` (INT) - Academic year
  - `room_number` (VARCHAR 20) - Room location
  - `schedule` (VARCHAR 100) - Class schedule
  - `created_at`, `updated_at` - Automatic timestamps

#### 5. Students (Õpilased)
- **Primary Key**: `student_id`
- **Foreign Keys**: `major_department_id` → Departments
- **Fields**:
  - `first_name`, `last_name` (VARCHAR 50, REQUIRED) - Name
  - `email` (VARCHAR 100, UNIQUE, REQUIRED) - Email address
  - `phone` (VARCHAR 20) - Phone number
  - `date_of_birth` (DATE) - Date of birth
  - `enrollment_year` (INT) - Year of enrollment
  - `major_department_id` (INT) - Major department
  - `gpa` (DECIMAL 3,2) - Grade Point Average (0.00 to 5.00)
  - `created_at`, `updated_at` - Automatic timestamps

#### 6. Enrollments (Registreerimised/Hinded)
- **Primary Key**: `enrollment_id`
- **Foreign Keys**: 
  - `student_id` → Students
  - `course_id` → Courses
- **Fields**:
  - `student_id` (INT, REQUIRED) - Enrolled student
  - `course_id` (INT, REQUIRED) - Enrolled course
  - `enrollment_date` (DATE, REQUIRED) - Enrollment date
  - `grade` (VARCHAR 2) - Final grade (e.g., "A", "MA", "5", "4")
  - `status` (VARCHAR 20, DEFAULT 'Active') - Status: Active, Completed, Dropped, Failed
  - `created_at`, `updated_at` - Automatic timestamps
- **Rules**: 
  - UNIQUE(student_id, course_id) - Student can enroll in a course only once

## Relationships

- Each department can have multiple instructors, courses, and students
- Each instructor belongs to one department and can teach multiple courses
- Each course belongs to one department and is taught by one instructor
- Each student belongs to one major department
- Students and courses have a many-to-many relationship through enrollments
- Each department can have one department head (one-to-one)

## Functional Requirements

### Department Management
- Add multiple departments
- Each department has unique name and location
- Track budget for financial planning

### Instructor Management
- Instructors belong to one specific department
- Store first name, last name, and contact information
- Store salary information and payment history
- Support multiple contact phones or addresses
- Instructors can teach multiple courses to multiple groups

### Student Management
- Students belong to one group
- Store first name and last name
- Same names can exist in different groups
- Students have representatives

### Course Management
- Courses linked to instructors and departments
- Store subject name and date
- Each course belongs to specific department

### Grade Management
- Each grade linked to specific student and course
- Allowed grades: 1-5, A, MA
- Optional free-text comments (e.g., "absent", "late")

## User Scenarios

### User A (College Secretary)
- Add new department to college
- Add new student to group
- Check which instructors teach in group TA-23B

### User B (Instructor)
- Add student grades for their courses
- View grades given in specific course on specific date

### User C (Student or Parent)
- View summary of grades for last month

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Laravel 11.x

### Setup Steps

1. Clone the repository:
```bash
git clone https://github.com/lokr3m/college-db.git
cd college-db
```

2. Install dependencies (when composer.json includes Laravel dependencies):
```bash
composer install
```

3. Configure environment:
```bash
cp .env.example .env
# Edit .env file with your database credentials
```

4. Set database connection in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=college_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations to create database tables:
```bash
php artisan migrate
```

6. Seed the database with sample data:
```bash
php artisan db:seed
```

## Database Migrations

All migrations are located in `database/migrations/` directory:

- `2024_01_01_000001_create_departments_table.php`
- `2024_01_01_000002_create_instructors_table.php`
- `2024_01_01_000003_create_department_heads_table.php`
- `2024_01_01_000004_create_courses_table.php`
- `2024_01_01_000005_create_students_table.php`
- `2024_01_01_000006_create_enrollments_table.php`

## Eloquent Models

All models are located in `app/Models/` directory:

- `Department.php` - Department model with relationships
- `Instructor.php` - Instructor model with relationships
- `DepartmentHead.php` - Department head model
- `Course.php` - Course model with relationships
- `Student.php` - Student model with relationships
- `Enrollment.php` - Enrollment pivot model

## Sample Data

The database seeder includes:
- 3 Departments (Computer Science, Mathematics, Business Administration)
- 4 Instructors
- 3 Department Heads
- 4 Courses
- 4 Students
- 6 Enrollments

## Non-Functional Requirements

- ✅ System supports database extensibility (new departments, subjects)
- ✅ Data is relationally connected, avoiding data duplication (normalization)
- ✅ All fields use appropriate data types
- ✅ Database supports filtering and joining data (JOINs)
- ✅ Reasonable data insertion and query performance

## Future Development

This is the initial setup. Future additions may include:
- Web interface for data management
- API endpoints for CRUD operations
- Advanced reporting features
- User authentication and authorization
- Group/class management
- Attendance tracking

## License

MIT License
