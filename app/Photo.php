<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    public function posts(){
        return $this->hasMany('App\Post','post_photos');
    }
}
