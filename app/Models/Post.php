<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['header', 'content', 'user_id', 'user_ip', 'avg_rating'];

    protected $hidden = ['user_id'];

    public function reviews(){
        return $this->hasMany('App\Models\Review');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
