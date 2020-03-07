<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MembershipRequest extends Model
{
    //
    protected $table = "membership_requests";
    public $timestamps = true;

    /**
	 * Atributos que se pueden modificar
	 *
	 * @var array
	 */
	protected $fillable = [
		'status', 'comment', 'user_id',
	];
    
    /**
	 *Filtra una solicitud de afiliación por el Id
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
	 *Filtra una solicitud de afiliación por el usuarioID
	 *
	 * @param  mixed $query
	 * @param  integer $user_id
	 * @return mixed
	 */
    public function scopeUserId($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /* TODO:Relaciones del Modelo
    */

    /*Una solicitud de afiliación pertenece a un usuario */
   public function user()
   {
       return $this->belongsTo(User::class);
   }

}
