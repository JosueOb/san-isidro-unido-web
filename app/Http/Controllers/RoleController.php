<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ProtectedAdminRole;
use App\Http\Middleware\ProtectedAppRoles;
use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\{Role, Permission};
use App\Http\Requests\RoleRequest;



class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectedAdminRole::class)->only('edit', 'update');
        $this->middleware(ProtectedAppRoles::class)->only('edit', 'update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Se obtienen los roles del sistema web
        $webSystemRoles = Role::where('mobile_app', false)->paginate();
        //Se obtienen los roles de la aplicación móvil
        $appRoles = Role::where('mobile_app', true)->paginate();

        return view('roles.index',[
            'webSystemRoles'=>$webSystemRoles,
            'appRoles'=> $appRoles,
        ]);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //Se obtienen todos los permisos registrados que no sean privados
    //     $permissions = Permission::where('private',false)->get();
    //     //Se retorna el formulario de registro de un rol
    //     return view('roles.create',[
    //         'permissions'=> $permissions,
    //     ]);
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(RoleRequest $request)
    // {
    //     $validated = $request->validated();

    //     $role = new Role();
    //     $role->name = $validated['name'];
    //     $role->slug = $validated['slug'];
    //     $role->description = $validated['description'];
    //     $role->private = false;
    //     $role->save();

    //     $role->permissions()->sync($validated['permissions']);

    //     return redirect()->route('roles.index')->with('success','Rol creado exitosamente');
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = $role->permissions()->get();
        //Se agrupan a los permisos acorde al grupo que pertenece
        $permissionGroup = $permissions->groupBy('group');
    
        return view('roles.show', [
            'role'=> $role,
            'permissionGroup'=>$permissionGroup,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //Se obtienen los permisos que son públicos
        $permissions = Permission::where('private',false)->get();

        //Se agrupan a los permisos acorde al grupo que pertenece
        $permissionGroup = $permissions->groupBy('group');

        //Se obtienen los permisos del rol
        $rolePermissions = $role->permissions()->get();

        return view('roles.edit', [
            'role'=>$role,
            'rolePermissions'=> $rolePermissions,
            'permissionGroup'=> $permissionGroup,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {

        $validated = $request->validated();
        // $role->name = $validated['name'];
        // $role->slug = $validated['slug'];
        $role->description = $validated['description'];
        $role->save();

        $role->permissions()->sync($validated['permissions']);

        return redirect()->route('roles.index')->with('success','Rol actualizado exitosamente');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Role $role)
    // {

    //     $hasUsers = $role->users()->get();

    //     if( count($hasUsers) > 0){
    //         return redirect()->route('roles.index')->with('danger','El rol '.strtolower($role->name).' no se puede eliminar ya que esta siendo utilizado' );
    //     }else{
    //         $role->delete();
    //         return redirect()->route('roles.index')->with('success','El rol '.strtolower($role->name).' a sido eliminado exitosamente' );
    //     }
    // }
}
