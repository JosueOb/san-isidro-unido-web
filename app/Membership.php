<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'memberships';
    public $timestamps = true;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['identity_card', 'basic_service_image'];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'responsible' => 'array',
    ];

    /**
     * A membership belongs to a user
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
