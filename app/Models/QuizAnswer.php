<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_answers';

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id', 'id');
    }
}
