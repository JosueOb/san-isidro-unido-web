<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = "phones";
    public $timestamps = true;
   
    /**
	 *Filtra un detalle por el Id
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
    * The attributes that are mass assignable.
    *
    * @var array
    */
    // protected $fillable = ['phone_number', 'public_service_id'];
    protected $fillable = ['phone_number'];
    /**
    * Get the owning phoneable model.
    */
    public function phoneable()
    {
        return $this->morphTo();
    }
}
