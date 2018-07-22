<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['rating', 'post_id'];

    public function post(){
        $this->belongsTo('App\Models\Post');
    }
}
