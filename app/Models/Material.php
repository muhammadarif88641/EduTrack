<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'file_path',
        'original_filename',
        'week_number',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
