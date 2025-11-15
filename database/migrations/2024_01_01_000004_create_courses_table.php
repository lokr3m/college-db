<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the courses table (Kursused/Õppeained).
     * Stores information about courses/subjects offered by departments.
     * Each course belongs to one department and is taught by one instructor.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            // Primary key - auto-incrementing ID
            $table->id('course_id');
            
            // Unique course code identifier
            // Example: "CS101", "MATH200", "BUS305"
            // Must be unique across all courses
            $table->string('course_code', 20)->unique();
            
            // Full name of the course
            // Example: "Introduction to Programming", "Calculus I"
            $table->string('course_name', 100);
            
            // Department offering this course (REQUIRED)
            // Foreign key to departments table
            $table->unsignedBigInteger('department_id');
            
            // Instructor teaching this course (optional - can be assigned later)
            // Foreign key to instructors table
            $table->unsignedBigInteger('instructor_id')->nullable();
            
            // Credit points/hours for this course
            // Default: 3 credits (typical for most courses)
            // Range: typically 1-6 credits
            $table->integer('credits')->default(3);
            
            // Semester when course is offered
            // Example: "Autumn 2024", "Spring 2025", "Fall 2024"
            // Format: flexible text to accommodate different semester naming conventions
            $table->string('semester', 20)->nullable();
            
            // Academic year when course runs
            // Example: 2024, 2025
            // Used together with semester for precise scheduling
            $table->integer('year')->nullable();
            
            // Physical room/location where course is held
            // Example: "A-101", "B-404", "Lab 3", "Online"
            // Helps students find where classes take place
            $table->string('room_number', 20)->nullable();
            
            // Weekly schedule for the course
            // Example: "Mon/Wed 10:00-11:30", "Tue/Thu 14:00-16:00"
            // Free-text format to accommodate various scheduling patterns
            $table->string('schedule', 100)->nullable();
            
            // Timestamps: created_at and updated_at (automatically managed by Laravel)
            $table->timestamps();

            // Foreign key constraint: course must belong to a valid department
            // ON DELETE RESTRICT: Cannot delete department if courses exist
            $table->foreign('department_id')
                  ->references('department_id')
                  ->on('departments')
                  ->onDelete('restrict');

            // Foreign key constraint: course can be taught by an instructor
            // ON DELETE SET NULL: If instructor is deleted, course remains but instructor_id becomes null
            $table->foreign('instructor_id')
                  ->references('instructor_id')
                  ->on('instructors')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
