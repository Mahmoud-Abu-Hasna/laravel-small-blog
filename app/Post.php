<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    public function categories(){
        return $this->belongsToMany('App\Category','posts_categories');
    }
    public function photos(){
        return $this->belongsToMany('App\Photo','post_photos');
    }
    public function tags(){
        return $this->belongsToMany('App\Tag','post_tags');
    }
    public function comments(){
        return $this->hasMany('App\Comment');
    }
}
