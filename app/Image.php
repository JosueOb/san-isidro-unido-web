<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['url', 'post_id'];

    /**
    * get the picrures
    */
    public function getUrlAttribute($image){
        return \Storage::disk('public')->url($image);
    }
    /**
    * An image belongs to a post
    */
    public function post(){
        return $this->belongsTo(Post::class);
    }

}
