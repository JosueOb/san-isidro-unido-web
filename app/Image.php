<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';
    /**
    * An image belongs to a post
    */
    public function post(){
        return $this->belongsTo(Post::class);
    }
}
