<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Phone;
use App\Subcategory;

class PublicService extends Model
{
    protected $table = "public_services";
    public $timestamps = true;

    protected $fillable = ['name', 'description'];
    
    protected $casts = [
        'ubication' => 'array'
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
	 *Relacion uno a muchos con la tabla Phones
	 *
	 * @return mixed
	 */
    public function phones()
    {
        return $this->hasMany(Phone::class);
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
