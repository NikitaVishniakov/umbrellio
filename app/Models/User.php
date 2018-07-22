<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];
    protected $fillable = ['login'];
    public $timestamps = false;

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }
}
