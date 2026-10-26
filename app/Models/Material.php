<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    // Columns that Laravel can save
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'file_path',
        'original_filename',
    ];

    // Each learning material belongs to one course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
