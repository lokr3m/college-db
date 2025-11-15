<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the department_heads table (Osakonnajuhatajad).
     * Manages the one-to-one relationship between departments and their heads.
     * 
     * IMPORTANT NOTES:
     * - Each department can have AT MOST one head at any time
     * - Each instructor can head AT MOST one department
     * - This table stores ONLY the CURRENT head (no historical records)
     * - If you need to track head history, this table structure would need modification
     * 
     * Business Rules:
     * - A department can exist without a head (optional relationship)
     * - When a new head is assigned, the old record should be updated or replaced
     * - Deleting a department automatically removes the head assignment (CASCADE)
     * - Deleting an instructor automatically removes their head assignment (CASCADE)
     */
    public function up(): void
    {
        Schema::create('department_heads', function (Blueprint $table) {
            // Department identifier - also serves as primary key
            // This ensures one department = one head maximum
            $table->unsignedBigInteger('department_id')->primary();
            
            // Instructor who is the head - must be unique
            // This ensures one instructor can head only one department
            $table->unsignedBigInteger('instructor_id')->unique();
            
            // Date when this instructor became the department head
            // For tracking when current leadership started
            $table->date('start_date');
            
            // Timestamps: created_at and updated_at (automatically managed by Laravel)
            $table->timestamps();

            // Foreign key to departments table
            // ON DELETE CASCADE: If department is deleted, head record is also deleted
            $table->foreign('department_id')
                  ->references('department_id')
                  ->on('departments')
                  ->onDelete('cascade');

            // Foreign key to instructors table
            // ON DELETE CASCADE: If instructor is deleted, head record is also deleted
            $table->foreign('instructor_id')
                  ->references('instructor_id')
                  ->on('instructors')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_heads');
    }
};
