<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id','question','options','correct_answer','difficulty','order','is_active','level'
    ];

    protected $casts = [
        'options'   => 'array',
        'is_active' => 'boolean',
        'order'     => 'integer',
        'correct_answer' => 'integer',
        'level'     => 'integer',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
