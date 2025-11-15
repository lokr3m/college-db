<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the students table (Õpilased).
     * Stores information about students enrolled in the college.
     * Students can have a major department but it's optional.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            // Primary key - auto-incrementing ID
            $table->id('student_id');
            
            // Student's first name (required)
            $table->string('first_name', 50);
            
            // Student's last name (required)
            $table->string('last_name', 50);
            
            // Email address - must be unique across all students
            // Used for contact and potentially login to student portal
            $table->string('email', 100)->unique();
            
            // Phone number for contact
            // Format: flexible to accommodate international formats
            $table->string('phone', 20)->nullable();
            
            // Student's date of birth
            // Used for age verification, student records, etc.
            $table->date('date_of_birth')->nullable();
            
            // Year when student enrolled/started studies
            // Example: 2022, 2023, 2024
            // Helps track student cohorts and expected graduation year
            $table->integer('enrollment_year')->nullable();
            
            // Student's major/primary department (optional)
            // Foreign key to departments table
            // Example: Computer Science major, Mathematics major
            $table->unsignedBigInteger('major_department_id')->nullable();
            
            // Grade Point Average (GPA)
            // Estonian scale: 0.00 to 5.00 (where 5.00 is excellent)
            // Format: DECIMAL(3,2) allows values like 4.75, 3.50, etc.
            $table->decimal('gpa', 3, 2)->nullable();
            
            // Timestamps: created_at and updated_at (automatically managed by Laravel)
            $table->timestamps();

            // Foreign key constraint: student's major must be a valid department
            // ON DELETE SET NULL: If department is deleted, student remains but major becomes null
            $table->foreign('major_department_id')
                  ->references('department_id')
                  ->on('departments')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
