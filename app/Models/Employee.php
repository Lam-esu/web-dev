<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'employee_number',
        'department_position',
        'picture',
    ];

    /**
     * User account belonging to this employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Attendance records belonging to this employee.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}