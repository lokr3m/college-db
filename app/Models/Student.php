<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'enrollment_year',
        'major_department_id',
        'gpa',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_year' => 'integer',
        'gpa' => 'decimal:2',
    ];

    /**
     * Get the major department of the student.
     */
    public function majorDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'major_department_id', 'department_id');
    }

    /**
     * Get the courses the student is enrolled in.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
                    ->using(Enrollment::class)
                    ->withPivot(['enrollment_date', 'grade', 'status'])
                    ->withTimestamps();
    }

    /**
     * Get the enrollments for the student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'student_id');
    }
}
