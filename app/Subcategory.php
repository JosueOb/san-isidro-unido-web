<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcategories';
    /**
    * A subcategory can have many posts
    */
    public function posts(){
        return $this->hasMany(Post::class);
    }
     /**
    * A subcategory belongs to a category
    */
    public function category(){
        return $this->belongsTo(Category::class);
    }
        /**
    * A subcategory can have many public services
    */
    public function publicServices(){
        return $this->hasMany(PublicService::class);
    }
    /**
    * get resoruce link
    */
    public function getLink(){
        $icon = $this->icon;
        if(!$icon || starts_with($icon,'http')){
            return $icon;
        }
        return \Storage::disk('s3')->url($this->icon);
    }
}
