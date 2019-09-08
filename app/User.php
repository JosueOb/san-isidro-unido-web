<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;
use App\Notifications\{UserResetPassword, UserVerifyEmail};

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getWebSystemRoles(){
        //Se retorna los roles del usuario que pueden acceder al sistema
        // return $this->roles()->whereNotIn('name',['Morador','Invitado','Policia'])->first();
        return $this->roles()->where('mobile_app',false)->first();
    }

    //Se obtiene un específico rol del usuario
    public function getASpecificRole($roleSlug){
        return $this->roles()->where('slug', $roleSlug)->first();
    }

    //Obtener el estado de la realción entre roles y usuarios
    //Se obtiene el valor de la columna state de la tabla pivote entre roles y usuarios
    public function getRelationshipStateRolesUsers($roleSlug){
        $role = $this->roles()->where('slug', $roleSlug)->first();
        $state = $role->pivot->state;
        return $state;
    }
    
    //Se sobrescribe el método sendPasswordNotificatión para cambiar a un nuevo objeto 
    //de la clase UserResetNotification con el contenido de la notificación traducida
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPassword($token));
    }
    //Se sobrescribe el método sendEmailVerificationNotification para cambiar a un nuevo objeto 
    //de la clase UserResetNotification con el contenido de la notificación traducida
    public function sendEmailVerificationNotification(){
        $this->notify(new UserVerifyEmail);
    }
    /*
    *Se obtiene la posición a la que pertenece el usuario
    */
    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function getAvatar(){
        $avatar = $this->avatar;
        if(!$avatar || \starts_with($avatar,'http')){
            return $avatar;
        }
        return \Storage::disk('public')->url($avatar);
    }

    public function getFullName(){
        $first_name = explode(' ',$this->first_name);
        $last_name = explode(' ',$this->last_name);

        return "$first_name[0] $last_name[0]"; 
    }

    /**
     * Users can have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(config('shinobi.models.role'))->withPivot('state')->withTimestamps();
    }
}
