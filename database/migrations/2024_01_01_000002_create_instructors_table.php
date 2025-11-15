<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the instructors table (Õpetajad/Õppejõud).
     * Stores information about teachers/instructors.
     * Each instructor MUST belong to exactly one department.
     */
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            // Primary key - auto-incrementing ID
            $table->id('instructor_id');
            
            // Instructor's first name (required)
            $table->string('first_name', 50);
            
            // Instructor's last name (required)
            $table->string('last_name', 50);
            
            // Email address - must be unique across all instructors
            // Used for contact and potentially login
            $table->string('email', 100)->unique();
            
            // Phone number for contact
            // Format: flexible to accommodate international formats
            $table->string('phone', 20)->nullable();
            
            // Department affiliation (REQUIRED - instructor must belong to a department)
            // Foreign key to departments table
            $table->unsignedBigInteger('department_id');
            
            // Monthly or annual salary
            // Format: DECIMAL(10,2) allows up to 99,999,999.99
            $table->decimal('salary', 10, 2)->nullable();
            
            // Date when instructor was hired
            $table->date('hire_date')->nullable();
            
            // Timestamps: created_at and updated_at (automatically managed by Laravel)
            $table->timestamps();

            // Foreign key constraint: instructor must belong to a valid department
            // ON DELETE RESTRICT: Cannot delete department if instructors exist
            $table->foreign('department_id')
                  ->references('department_id')
                  ->on('departments')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
