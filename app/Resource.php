<?php

namespace App;

use App\Helpers\ApiImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

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

    //Add Extra Attributes

    /*AGREGAR RESOURCE LINK ATTRIBUTE */
    protected $attributes = ['resource_link'];
    protected $appends = ['resource_link'];
    public function getResourceLinkAttribute(){
        return $this->getApiLink();
    }

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
        return \Storage::disk('public')->url($this->url);
    }

    /**
    * get resource api link
    */
    public function getApiLink(){
        $imageApi = new ApiImages();
        return $imageApi->getApiUrlLink($this->url);
    }

}
