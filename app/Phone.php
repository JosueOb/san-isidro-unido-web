<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'phones';
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
