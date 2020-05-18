<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $table = "social_profiles";
    public $timestamps = true;


    /**
	 *Filtra un Perfil Social por su id
	 *
	 * @param  mixed $query
     * @param int $id
	 * @return mixed
	 */
    public function scopeFindById($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
	 *Filtra un Perfil Social por el ID del Usuario Asociado
	 *
	 * @param  mixed $query
	 * @param  int $userId
	 * @return mixed
	 */
    public function scopeUserId($query, string $userId) {
        return $query->where('user_id', $userId);
    }

    /**
	 *Filtra un Perfil Social por el SocialID
	 *
	 * @param  mixed $query
	 * @param  string $social_id
	 * @return mixed
	 */
    public function scopeSocialId($query, string $social_id) {
        return $query->where('social_id', $social_id);
    }

    /**
	 *Filtra un Perfil Social por el Provider
	 *
	 * @param  mixed $query
	 * @param  string $provider
	 * @return mixed
	 */
    public function scopeProvider($query, string $provider) {
        return $query->where('provider', $provider);
    }
}
