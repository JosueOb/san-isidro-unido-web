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
    
    public function getRol(){
        return $this->roles()->whereNotIn('name',['Morador'])->first();
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
