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

    /*TODO: SCOPES MODELO */ 

    
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
}
