<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'employee_number',
        'department_position',
        'picture',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}           