<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = ['id'];

    public function response()
    {
        return $this->belongsTo(Response::class, 'response_id');
    }
    public function questions()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }
}
