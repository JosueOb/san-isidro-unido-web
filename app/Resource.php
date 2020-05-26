<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resources';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['url', 'post_id', 'type'];

    /**
    * A resource belongs to a post
    */
    public function post(){
        return $this->belongsTo(Post::class);
    }
    /**
    * get resoruce link
    */
    public function getLink(){
        $url = $this->url;
        if(!$url || \starts_with($url,'http')){
            return $url;
        }
        return \Storage::disk('s3')->url($url);
    }

}
