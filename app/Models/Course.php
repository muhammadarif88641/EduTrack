<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;




class Course extends Model
{
    protected $fillable = [
        'lecturer_id',
        'course_code',
        'course_name',
        'description',
        'status',
    ];

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

     // A course can have many learning materials
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

        public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
