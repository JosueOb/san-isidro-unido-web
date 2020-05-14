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
        $role->description = $validated['description'];
        $role->save();

        $role->permissions()->sync($validated['permissions']);

        return redirect()->route('roles.index')->with('success','Rol actualizado exitosamente');
    }
}
