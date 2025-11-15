<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'department_id';

    protected $fillable = [
        'department_name',
        'building',
        'budget',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    /**
     * Get the instructors for the department.
     */
    public function instructors(): HasMany
    {
        return $this->hasMany(Instructor::class, 'department_id', 'department_id');
    }

    /**
     * Get the courses for the department.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'department_id', 'department_id');
    }

    /**
     * Get the students majoring in this department.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'major_department_id', 'department_id');
    }

    /**
     * Get the department head.
     */
    public function departmentHead(): HasOne
    {
        return $this->hasOne(DepartmentHead::class, 'department_id', 'department_id');
    }
}
