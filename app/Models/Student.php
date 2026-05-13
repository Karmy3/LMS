<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'enrolled_at'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
