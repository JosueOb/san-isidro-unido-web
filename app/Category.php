<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    /**
    * A category can have many posts
    */
    public function posts(){
        return $this->hasMany(Post::class);
    }
     /**
    * A category can have many subcategories
    */
    public function subcategories(){
        return $this->hasMany(Subcategory::class);
    }
    /**
    * A category can have many public services
    */
    public function publicServices(){
        return $this->hasMany(PublicService::class);
    }
    /**
    * get resoruce link
    */
    public function getLink(){
        return \Storage::disk('public')->url($this->icon);
    }
}
