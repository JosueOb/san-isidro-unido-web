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
        return $this->roles()->where('mobile_app', false)->get();
    }

    //Se obtiene un específico rol del usuario
    public function getASpecificRole($roleSlug){
        return $this->roles()->where('slug', $roleSlug)->first();
    }

    //Obtener el estado de la realción entre roles y usuarios
    //Se obtiene el valor de la columna state de la tabla pivote entre roles y usuarios
    public function getRelationshipStateRolesUsers($roleSlug){
        $state = false;
        $role = $this->roles()->where('slug', $roleSlug)->first();
        if($role){
            $state = $role->pivot->state;
        }
        return $state;
    }

    //Se verifica que algún rol del sistema web asignados al usuario se encuentre activo
    public function hasSomeActiveWebSystemRole(){
        $hasSomeActiveRol = false;
        $userRoles = $this->getWebSystemRoles();
        foreach($userRoles as $role){
            if($this->getRelationshipStateRolesUsers($role->slug)){
                $hasSomeActiveRol = true;
            }
        }
        return $hasSomeActiveRol;
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
        return \Storage::disk('s3')->url($avatar);
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
    /**
     * A user can have many posts
     */
    public function posts(){
        return $this->hasMany(Post::class);
    }
    /**
     * The mothergoose check. Runs through each scenario provided
     * by Shinobi - checking for special flags, role permissions, and
     * individual user permissions; in that order.
     * 
     * @param  Permission  $permission
     * @return boolean
     */
    //Se sobrescribe la funciones del paquete shinobi
    public function hasPermissionTo($permission): bool
    {
        //Se obtiene los roles que tiene el permiso
        $permission_roles = $permission->roles;
        //USUARIO
        //Se obtiene al usuario que está realizando la petición
        $user = $this;
        //Se obtiene los roles que tiene el usuario
        $user_roles = $user->roles;
        //Se obtienen los roles tanto del usuario y permiso que tienen en común
        $common_roles = $permission_roles->intersect($user_roles);
        if($this->checkRoleState($common_roles, $user)){
            // Check role flags
            if ($this->hasPermissionFlags()) {
                return $this->hasPermissionThroughFlag();
            }

            // Check role permissions
            if ($this->hasPermissionThroughRole($permission)) {
                return true;
            }

            // Check user permission
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
     //Se verifica que de los roles obtenidos, uno de ellos tenga el usuario activado en su relación de rol y usuario
     private function checkRoleState($roles, $user){
        $state = false;
        foreach($roles as $role){
            if($user->getRelationshipStateRolesUsers($role->slug)){
                $state = true;
            }
        }
        return $state;
    }
}
