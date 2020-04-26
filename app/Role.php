<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Permission;
use App\RoleUser;

class Role extends Model {
    protected $table = "roles";
    public $timestamps = true;

    
    /**
	 *Filtra un Rol por el Id
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
	 *Filtra un ROL por el slug
	 *
	 * @param  mixed $query
	 * @param  string $slug
	 * @return mixed
	 */
    public function scopeSlug($query, string $slug) {
        return $query->where('slug', $slug);
    }

    /**
	 *Filtra un rol de tipo  Directivo
	 *
	 * @param  mixed $query
	 * @return mixed
	 */
    public function scopeRolDirectivo($query) {
        return $query->where('slug','directivo');
    }

	/**
     *Relacion de Pertenencia Muchos a Muchos con la tabla Users
     *
	 * @return mixed
	 */
	public function users() {
		return $this->belongsToMany(User::class)
			->using(RoleUser::class)
			->withPivot([
				'state',
			]);
	}

    /**
	 *Relacion de Pertenencia Muchos a muchos con la tabla Permissions
	 *
	 * @return mixed
	 */
	public function permissions() {
		return $this->belongsToMany(Permission::class);
	}

}
