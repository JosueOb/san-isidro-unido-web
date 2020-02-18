<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    //
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
    protected $fillable = ['phone_number', 'public_service_id'];
    /**
    * A phone belongs to a public service
    */
    public function publicService(){
        return $this->belongsTo(PublicService::class);
    }
}
