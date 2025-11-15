<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the enrollments table (Registreerimised/Hinded).
     * Manages the many-to-many relationship between students and courses.
     * Also stores grades and enrollment status for each student-course pair.
     * 
     * IMPORTANT NOTES:
     * - A student can enroll in multiple courses
     * - A course can have multiple students
     * - A student can enroll in the same course ONLY ONCE (enforced by unique constraint)
     * - Enrollments are deleted if either the student or course is deleted (CASCADE)
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            // Primary key - auto-incrementing ID
            $table->id('enrollment_id');
            
            // Student who is enrolled (REQUIRED)
            // Foreign key to students table
            $table->unsignedBigInteger('student_id');
            
            // Course in which student is enrolled (REQUIRED)
            // Foreign key to courses table
            $table->unsignedBigInteger('course_id');
            
            // Date when student enrolled in this course
            // Used to track registration history
            $table->date('enrollment_date');
            
            // Final grade received by student
            // Estonian grading system: "1", "2", "3", "4", "5" (numeric)
            // or "A" (Arvestatud/Passed), "MA" (Mittearvestatud/Failed)
            // NULL if grade not yet assigned
            $table->string('grade', 2)->nullable();
            
            // Current enrollment status
            // Values: "Active" (currently enrolled), "Completed" (finished with grade),
            //         "Dropped" (student withdrew), "Failed" (did not pass)
            // Default: "Active" for new enrollments
            $table->string('status', 20)->default('Active');
            
            // Timestamps: created_at and updated_at (automatically managed by Laravel)
            $table->timestamps();

            // Unique constraint: prevents a student from enrolling in the same course twice
            // Composite unique key on (student_id, course_id)
            $table->unique(['student_id', 'course_id']);

            // Foreign key constraint: enrollment must be for a valid student
            // ON DELETE CASCADE: If student is deleted, all their enrollments are deleted
            $table->foreign('student_id')
                  ->references('student_id')
                  ->on('students')
                  ->onDelete('cascade');

            // Foreign key constraint: enrollment must be for a valid course
            // ON DELETE CASCADE: If course is deleted, all enrollments in it are deleted
            $table->foreign('course_id')
                  ->references('course_id')
                  ->on('courses')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
