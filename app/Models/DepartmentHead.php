<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentHead extends Model
{
    use HasFactory;

    protected $primaryKey = 'department_id';
    public $incrementing = false;

    protected $fillable = [
        'department_id',
        'instructor_id',
        'start_date',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    /**
     * Get the department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * Get the instructor who is the head.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id', 'instructor_id');
    }
}
