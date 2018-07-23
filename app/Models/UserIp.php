<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    protected $guarded = ['id'];
    public $timestamps =  false;

    protected $hidden = ['user_id'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }
}
