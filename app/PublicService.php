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
    public function scopeFindBySubCategoryId($query, $subcategory_id)
    {
        return $query->where('subcategory_id', $subcategory_id);
    }

   /**
     * A public service belongs to a category
     */
    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }
}
