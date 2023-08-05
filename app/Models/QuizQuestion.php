<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id', 'id');
    }
}
