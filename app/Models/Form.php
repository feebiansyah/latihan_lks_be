<?php

namespace App\Models;

use App\Models\User;
use App\Models\AllowedDomains;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $guarded = ['id'];
    protected $table = 'forms';


    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function allowedDomains()
    {
        return $this->hasMany(AllowedDomains::class, 'form_id');
    }

    public function questions()
    {
        return $this->hasMany(Questions::class, 'form_id');
    }
}
