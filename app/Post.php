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
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['title', 'description'];

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
     /**
     * Get the first image belonging to a post
     */
    // public function getFirstImage(){
    //     $image = $this->images()->first();
    //     if($image){
    //         $image_url = $image["url"];
    //     }else{
    //         //en caso de no tener imagen se retorna una por defecto
    //         $image_url = "images_reports/image-default.jpg";
    //     }
    //     return  \Storage::disk('public')->url($image_url);
    // }
    /**
     * A post can have many resources
     */
    public function resources(){
        return $this->hasMany(Resource::class);
    }
    /**
     * Get the first image belonging to a post
     */
    public function getFirstImage(){
        $image = $this->resources()->where('type','image')->first();
        
        if($image){
            $image_url = $image["url"];
        }else{
            //en caso de no tener imagen se retorna una por defecto
            // $image_url = "images_reports/image-default.jpg";
            $image_url = 'images_reports/'.env('IMAGE_DEFAULT_NAME');
        }
        return  \Storage::disk('public')->url($image_url);
    }

}
