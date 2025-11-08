<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'is_correct',
        'points',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points' => 'integer',
    ];
}

