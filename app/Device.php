<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    //
    protected $table = "devices";
    public $timestamps = true;

    /**
	 * Atributos que se pueden modificar
	 *
	 * @var array
	 */
	protected $fillable = [
		'phone_id', 'description', 'user_id', 'phone_model'
	];
    
    /**
	 *Filtra un dispositivo por el Id
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
	 *Filtra un dispositivo por el PhoneId
	 *
	 * @param  mixed $query
	 * @param  integer $phone_id
	 * @return mixed
	 */
    public function scopeFindByPhoneId($query, $phone_id)
    {
        return $query->where('phone_id', $phone_id);
    }

    /**
	 *Filtra un dispositivo por el usuarioID
	 *
	 * @param  mixed $query
	 * @param  integer $user_id
	 * @return mixed
	 */
    public function scopeFindByUserId($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
	 *Filtra un dispositivo por el id del usuario asociado
	 *
	 * @param  mixed $query
	 * @param  integer $id
	 * @return mixed
	 */
    public function scopeUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }
    
    /**
	 *Filtra un dispositivo por el phone_id 
	 *
	 * @param  mixed $query
	 * @param  integer $phoneId
	 * @return mixed
	 */
    public function scopePhoneId($query, string $phoneId)
    {
        return $query->where('phone_id', $phoneId);
    }
}
