<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * A post belongs to a user
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
    /**
     * A post can have many images
     */
    public function images(){
        return $this->hasMany(Image::class);
    }
    /**
     * A post belongs to a category
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
    /**
     * A post belongs to a subcategory
     */
    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }
}
