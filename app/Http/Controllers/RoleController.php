<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\{Role, Permission};
use App\Http\Requests\{CreateRoleRequest, UpdateRoleRequest};
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Se listan los roles registrados
        $roles = Role::paginate();
        return view('roles.index',[
            'roles'=> $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Se obtienen todos los permisos registrados
        $permissions = Permission::all();
        //Se retorna el formulario de registro de un rol
        return view('roles.create',[
            'permissions'=> $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    {
        $validated = $request->validated();

        $selectedSpecialPermission = $request['special'];

        $role = new Role();
        $role->name = $validated['name'];
        $role->slug = $validated['slug'];
        $role->description = $validated['description'];
        $role->special = $selectedSpecialPermission;

        if($selectedSpecialPermission){
            $role->special = $selectedSpecialPermission;
            $role->save();
        }else{
            $role->save();
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('info','Rol creado exitosamente');
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
        
        return view('roles.show', [
            'role'=> $role,
            'permissions'=>$permissions,
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
        $permissions = Permission::all();
        $rolePermissions = $role->permissions()->get();

        return view('roles.edit', [
            'role'=>$role,
            'permissions'=>$permissions,
            'rolePermissions'=> $rolePermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        //
        $validated = $request->validated();
        $filter = Validator::make($validated,[
            'name'=>'unique:roles,name,'.$role->id,
            'slug'=>'unique:roles,slug,'.$role->id,
        ],[
            'name.unique'=>'El nombre ingresado ya existe',
            'slug.unique'=>'El slug ingresado ya existe',
        ])->validate();

        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}