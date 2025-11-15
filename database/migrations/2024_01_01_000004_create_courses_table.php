<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course_code', 20)->unique();
            $table->string('course_name', 100);
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->integer('credits')->default(3);
            $table->string('semester', 20)->nullable();
            $table->integer('year')->nullable();
            $table->string('room_number', 20)->nullable();
            $table->string('schedule', 100)->nullable();
            $table->timestamps();

            $table->foreign('department_id')
                  ->references('department_id')
                  ->on('departments')
                  ->onDelete('restrict');

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
