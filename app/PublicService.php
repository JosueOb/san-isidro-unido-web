<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Phone;
use App\Subcategory;

class PublicService extends Model
{
    protected $table = "public_services";
    public $timestamps = true;

    protected $fillable = ['name', 'email', 'public_opening'];
    
    protected $casts = [
        'ubication' => 'array',
        'public_opening' => 'array'
    ];

   /**
	 *Filtra un servicio publico por el Id
	 *
	 * @param  mixed $query
	 * @param  integer $id
	 * @return mixed
	 */
    public function scopeFindById($query, $id)
    {
        return $query->where('id', $id);
    }
    /**
    * Get all of the public service's phones.
    */
    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

   /**
	 *Filtra un servicio publico por el id de categoria
	 *
	 * @param  mixed $query
	 * @param  integer $category_id
	 * @return mixed
	 */
    public function scopeFindByCategoryId($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }

    /**
	 *Relacion de Pertenencia Uno a Uno con la Tabla Categories
	 *
	 * @return mixed
	 */
    public function subcategory(){
        return $this->belongsTo(SubCategory::class, "category_id")->orderBy('id','DESC');
    }
}
