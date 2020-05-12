<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
     /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'positions';
    public $timestamps = true;
    

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['name', 'description'];

    /**
	 *Filtra un cargo por el Id
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
    * Se  obtiene a los usuarios de una posición
    */
    public function users(){
        return $this->hasMany(User::class);
    }
}
