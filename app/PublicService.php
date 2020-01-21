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
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
