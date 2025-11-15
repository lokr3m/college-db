<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Pivot
{
    protected $table = 'enrollments';
    protected $primaryKey = 'enrollment_id';
    public $incrementing = true;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'grade',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    /**
     * Get the student for the enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get the course for the enrollment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
