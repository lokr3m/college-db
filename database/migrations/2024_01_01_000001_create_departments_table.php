<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the departments table (Osakonnad/Kolledzid).
     * Stores information about college departments/schools.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            // Primary key - auto-incrementing ID
            $table->id('department_id');
            
            // Department/college name - must be unique across all departments
            // Example: "Computer Science", "Mathematics", "Business Administration"
            $table->string('department_name', 100)->unique();
            
            // Building/location where the department is housed
            // Example: "Building A", "Main Campus Block C"
            $table->string('building', 100)->nullable();
            
            // Department budget for financial planning
            // Format: DECIMAL(12,2) allows up to 9,999,999,999.99
            $table->decimal('budget', 12, 2)->nullable();
            
            // Timestamps: created_at and updated_at (automatically managed by Laravel)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
