<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id','title','description','theory_content','video_url',
        'type','order','is_active','optionA','optionB','optionC','optionD','correct_option'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
