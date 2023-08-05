<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_results';

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id', 'id');
    }

}
