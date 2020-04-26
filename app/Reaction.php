<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Reaction extends Model
{
    protected $table = "reactions";
    public $timestamps = true;
    
    /**
	 *Filtra un detalle por su ID
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
	 *Filtra un dispositivo por su Tipo
	 *
	 * @param  mixed $query
	 * @param  string $type
	 * @return mixed
	 */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
	 *Filtra un detalle por su post_id
	 *
	 * @param  mixed $query
	 * @param  integer $postId
	 * @return mixed
	 */
    public function scopePostId($query, string $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
	 *Filtra un detalle por el Id del usuario asociado
	 *
	 * @param  mixed $query
	 * @param  integer $userId
	 * @return mixed
	 */
    public function scopeUserId($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

     /**
     *Relacion Uno a Uno con la tabla Usuarios
     * para obtener el usuario de un detalle
	 *
	 * @return mixed
	 */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
