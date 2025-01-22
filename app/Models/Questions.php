<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'questions';
    protected $guarded = ['id'];

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
