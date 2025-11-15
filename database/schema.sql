-- College Database Schema
-- MySQL Database Structure

-- Create Database
CREATE DATABASE IF NOT EXISTS college_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE college_db;

-- Table: Departments (Osakonnad/Koolid)
CREATE TABLE departments (
    department_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE,
    building VARCHAR(100),
    budget DECIMAL(12, 2),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: Instructors (Õpetajad/Õppejõud)
CREATE TABLE instructors (
    instructor_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    department_id BIGINT UNSIGNED NOT NULL,
    salary DECIMAL(10, 2),
    hire_date DATE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (department_id) 
        REFERENCES departments(department_id) 
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: DepartmentHeads (Osakonnajuhatajad)
CREATE TABLE department_heads (
    department_id BIGINT UNSIGNED PRIMARY KEY,
    instructor_id BIGINT UNSIGNED NOT NULL UNIQUE,
    start_date DATE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (department_id) 
        REFERENCES departments(department_id) 
        ON DELETE CASCADE,
    
    FOREIGN KEY (instructor_id) 
        REFERENCES instructors(instructor_id) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: Courses (Kursused/Õppeained)
CREATE TABLE courses (
    course_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    department_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED,
    credits INT NOT NULL DEFAULT 3,
    semester VARCHAR(20),
    year INT,
    room_number VARCHAR(20),
    schedule VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (department_id) 
        REFERENCES departments(department_id) 
        ON DELETE RESTRICT,
    
    FOREIGN KEY (instructor_id) 
        REFERENCES instructors(instructor_id) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: Students (Õpilased)
CREATE TABLE students (
    student_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    date_of_birth DATE,
    enrollment_year INT,
    major_department_id BIGINT UNSIGNED,
    gpa DECIMAL(3, 2),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (major_department_id) 
        REFERENCES departments(department_id) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: Enrollments (Registreerimised/Hinded)
CREATE TABLE enrollments (
    enrollment_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    enrollment_date DATE NOT NULL,
    grade VARCHAR(2),
    status VARCHAR(20) NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    UNIQUE KEY unique_enrollment (student_id, course_id),
    
    FOREIGN KEY (student_id) 
        REFERENCES students(student_id) 
        ON DELETE CASCADE,
    
    FOREIGN KEY (course_id) 
        REFERENCES courses(course_id) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for better performance
CREATE INDEX idx_instructors_department ON instructors(department_id);
CREATE INDEX idx_instructors_email ON instructors(email);
CREATE INDEX idx_courses_department ON courses(department_id);
CREATE INDEX idx_courses_instructor ON courses(instructor_id);
CREATE INDEX idx_courses_code ON courses(course_code);
CREATE INDEX idx_students_major_department ON students(major_department_id);
CREATE INDEX idx_students_email ON students(email);
CREATE INDEX idx_enrollments_student ON enrollments(student_id);
CREATE INDEX idx_enrollments_course ON enrollments(course_id);
CREATE INDEX idx_enrollments_status ON enrollments(status);
