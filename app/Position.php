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
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['name'];

    /**
    * Se  obtiene a los usuarios de una posición
    */
    public function users(){
        return $this->hasMany(User::class);
    }
}
