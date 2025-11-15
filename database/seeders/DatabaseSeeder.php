<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('enrollments')->truncate();
        DB::table('courses')->truncate();
        DB::table('students')->truncate();
        DB::table('department_heads')->truncate();
        DB::table('instructors')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed Departments
        $departments = [
            [
                'department_name' => 'Computer Science',
                'building' => 'Building A',
                'budget' => 500000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_name' => 'Mathematics',
                'building' => 'Building B',
                'budget' => 350000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_name' => 'Business Administration',
                'building' => 'Building C',
                'budget' => 450000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('departments')->insert($departments);

        // Seed Instructors
        $instructors = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@college.edu',
                'phone' => '+372-555-0001',
                'department_id' => 1,
                'salary' => 65000.00,
                'hire_date' => '2020-09-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria.garcia@college.edu',
                'phone' => '+372-555-0002',
                'department_id' => 1,
                'salary' => 68000.00,
                'hire_date' => '2019-08-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'email' => 'david.johnson@college.edu',
                'phone' => '+372-555-0003',
                'department_id' => 2,
                'salary' => 62000.00,
                'hire_date' => '2021-01-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.williams@college.edu',
                'phone' => '+372-555-0004',
                'department_id' => 3,
                'salary' => 70000.00,
                'hire_date' => '2018-07-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('instructors')->insert($instructors);

        // Seed Department Heads
        $departmentHeads = [
            [
                'department_id' => 1,
                'instructor_id' => 2,
                'start_date' => '2022-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 2,
                'instructor_id' => 3,
                'start_date' => '2023-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => 3,
                'instructor_id' => 4,
                'start_date' => '2021-09-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('department_heads')->insert($departmentHeads);

        // Seed Courses
        $courses = [
            [
                'course_code' => 'CS101',
                'course_name' => 'Introduction to Programming',
                'department_id' => 1,
                'instructor_id' => 1,
                'credits' => 3,
                'semester' => 'Autumn 2024',
                'year' => 2024,
                'room_number' => 'A-101',
                'schedule' => 'Mon/Wed 10:00-11:30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'CS201',
                'course_name' => 'Data Structures',
                'department_id' => 1,
                'instructor_id' => 2,
                'credits' => 4,
                'semester' => 'Autumn 2024',
                'year' => 2024,
                'room_number' => 'A-102',
                'schedule' => 'Tue/Thu 14:00-16:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'MATH101',
                'course_name' => 'Calculus I',
                'department_id' => 2,
                'instructor_id' => 3,
                'credits' => 4,
                'semester' => 'Autumn 2024',
                'year' => 2024,
                'room_number' => 'B-201',
                'schedule' => 'Mon/Wed/Fri 09:00-10:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'BUS101',
                'course_name' => 'Business Fundamentals',
                'department_id' => 3,
                'instructor_id' => 4,
                'credits' => 3,
                'semester' => 'Autumn 2024',
                'year' => 2024,
                'room_number' => 'C-301',
                'schedule' => 'Tue/Thu 10:00-11:30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('courses')->insert($courses);

        // Seed Students
        $students = [
            [
                'first_name' => 'Anna',
                'last_name' => 'Kask',
                'email' => 'anna.kask@student.college.edu',
                'phone' => '+372-555-1001',
                'date_of_birth' => '2003-05-15',
                'enrollment_year' => 2023,
                'major_department_id' => 1,
                'gpa' => 4.20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Peeter',
                'last_name' => 'Tamm',
                'email' => 'peeter.tamm@student.college.edu',
                'phone' => '+372-555-1002',
                'date_of_birth' => '2002-08-22',
                'enrollment_year' => 2022,
                'major_department_id' => 1,
                'gpa' => 3.80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Liis',
                'last_name' => 'Mets',
                'email' => 'liis.mets@student.college.edu',
                'phone' => '+372-555-1003',
                'date_of_birth' => '2003-11-30',
                'enrollment_year' => 2023,
                'major_department_id' => 2,
                'gpa' => 4.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Martin',
                'last_name' => 'Saar',
                'email' => 'martin.saar@student.college.edu',
                'phone' => '+372-555-1004',
                'date_of_birth' => '2002-03-10',
                'enrollment_year' => 2022,
                'major_department_id' => 3,
                'gpa' => 3.90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('students')->insert($students);

        // Seed Enrollments
        $enrollments = [
            [
                'student_id' => 1,
                'course_id' => 1,
                'enrollment_date' => '2024-09-01',
                'grade' => '5',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 1,
                'course_id' => 2,
                'enrollment_date' => '2024-09-01',
                'grade' => 'A',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'course_id' => 1,
                'enrollment_date' => '2024-09-01',
                'grade' => '4',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'course_id' => 3,
                'enrollment_date' => '2024-09-01',
                'grade' => null,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'course_id' => 3,
                'enrollment_date' => '2024-09-01',
                'grade' => 'MA',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 4,
                'course_id' => 4,
                'enrollment_date' => '2024-09-01',
                'grade' => '5',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('enrollments')->insert($enrollments);

        $this->command->info('Database seeded successfully!');
    }
}
