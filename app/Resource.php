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

    /*Add Extra Attributes*/

    /*AGREGAR RESOURCE LINK ATTRIBUTE */
    protected $appends = ['url_link'];
    public function getUrlLinkAttribute(){
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
        $url = $this->url;
        if(!$url || \starts_with($url,'http')){
            return $url;
        }
        return \Storage::disk('s3')->url($url);
    }

    /**
    * get resource api link
    */
    public function getApiLink(){
        $imageApi = new ApiImages();
        return $imageApi->getApiUrlLink($this->url);
    }

}
