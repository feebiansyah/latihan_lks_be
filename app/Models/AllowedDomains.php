<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedDomains extends Model
{
    protected $guarded = ['id'];
    protected $table = 'allowed_domains';

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
