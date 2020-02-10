<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicService extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'public_services';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['name', 'description'];
    /**
     * A public service belongs to a category
     */
    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }
     /**
    * A public service can have many phones
    */
    public function phones(){
        return $this->hasMany(Phone::class);
    }
}
