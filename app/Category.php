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
	 *Filtra una categoria por su slug
	 *
	 * @param  mixed $query
	 * @param  string $slug
	 * @return mixed
	 */
    public function scopeSlug($query, string $slug) {
        return $query->where('slug', $slug);
    }
}
