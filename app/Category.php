<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ApiImages;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /*TODO: SCOPES MODELO */ 
    /*AGREGAR RESOURCE LINK ATTRIBUTE */
    // protected $attributes = ['image_link'];
    protected $appends = ['image_link'];
    public function getImageLinkAttribute(){
        return $this->getApiLink();
    }
    
    /**
	 *Filtra una categoria por su slug
	 *
	 * @param  mixed $query
	 * @param  string $slug
	 * @return mixed
	 */
    public function scopeSlug($query, string $slug) {
        return $query->where('slug', $slug);
    }

    /**
	 *Filtra una categoria por su ID
	 *
	 * @param  mixed $query
	 * @param  integer $id
	 * @return mixed
	 */
    public function scopeFindById($query, $id) {
        return $query->where('id', $id);
    }

    /*TODO: RELACIONES MODELO */ 

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

    /*TODO: FUNCIONES EXTRA MODELO */ 
    /**
    * get resoruce link
    */
    public function getLink(){
        return \Storage::disk('public')->url($this->icon);
    }

      
    /**
    * get resource api link
    */
    public function getApiLink(){
        $imageApi = new ApiImages();
        return $imageApi->getApiUrlLink($this->image);
        // return $image_link;
        // $diskname = \Config::get('siu_config.API_IMAGES_DISK');
        // $file = $this->image;
        // return null;
        // if (\Storage::disk($diskname)->exists($file)) {
        //     return \Storage::disk($diskname)->url($file);
        // }
        // return "https://ui-avatars.com/api/?name=Siu+Categoria";
    }
}
