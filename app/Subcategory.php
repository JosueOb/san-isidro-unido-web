<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PublicService;
use App\Category;
use App\Post;
use App\Helpers\ApiImages;

class Subcategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcategories';
    public $timestamps = true;

    /*TODO: SCOPES MODELO */ 

    /**
	 *Filtra una subcategoria por el ID
	 *
	 * @param  mixed $query
	 * @param  int $id
	 * @return mixed
	 */
    public function scopeFindById($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
	 *Filtra una subcategoria por su slug
	 *
	 * @param  mixed $query
	 * @param  string $slug
	 * @return mixed
	 */
    public function scopeSlug($query, $slug) {
        return $query->where('slug', $slug);
    }

    /**
	 *Filtra una subcategoria por la categoria asociada
	 *
	 * @param  mixed $query
	 * @param  string $slug
	 * @return mixed
	 */
    public function scopeCategoryId($query, $category_id) {
        return $query->where('category_id', $category_id);
    }

    /*AGREGAR RESOURCE LINK ATTRIBUTE */
    // protected $attributes = ['image_link'];
    protected $appends = ['image_link'];
    public function getImageLinkAttribute(){
        return $this->getApiLink();
    }

    /*TODO: RELACIONES MODELO */ 

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
     *Relacion Uno a Muchos con la tabla Servicios Públicos
     * para obtener los servicios publicos de una categoria
	 *
	 * @return mixed
	 */
    public function publicServices()
    {
        return $this->hasMany(PublicService::class)->orderBy('id', 'DESC');
    }

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
    }
}
