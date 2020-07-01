<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ApiImages;

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

    protected $appends = ['basic_service_image_link'];
    public function getBasicServiceImageLinkAttribute(){
        return $this->getApiLink();
    }

    /**
     * A membership belongs to a user
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
    * get resource api link
    */
    public function getApiLink(){
        $imageApi = new ApiImages();
        return $imageApi->getApiUrlLink($this->url);
    }

    
}
