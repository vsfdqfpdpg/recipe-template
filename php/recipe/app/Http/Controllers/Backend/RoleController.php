<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RoleAssignPermissionRequest;
use App\Http\Requests\Backend\RoleStoreRequest;
use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderByDesc('id')->paginate(20);
        return view("backend.roles.list", ["roles" => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("backend.roles.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        $validated = $request->validated();
        $active = $request->active ?  true : false;
        Role::create([
            "title" => $validated["title"],
            "slug" => $validated["title"],
            "description" => $validated["description"],
            "active" => $active
        ]);
        return redirect()->route("admin.roles");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Backend\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->load("permissions");
        return view("backend.roles.show", ["role" => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Backend\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view("backend.roles.edit", ["role" => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Backend\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleStoreRequest $request, Role $role)
    {
        $validated = $request->validated();
        $active = $request->active ?  true : false;

        $role->title = $validated["title"];
        $role->slug = $validated["title"];
        $role->description = $validated["description"];
        $role->active = $active;
        $role->save();
        return redirect()->route("admin.roles");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backend\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function delete(Role $role)
    {
        return view("backend.roles.delete", ['role' => $role]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backend\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route("admin.roles");
    }


    public function assign(Role $role)
    {
        $permissions = Permission::all();
        $role->load("permissions");
        return view("backend.roles.assign", ["role" => $role, "permissions" => $permissions]);
    }

    public function storeAssign(RoleAssignPermissionRequest $request, Role $role)
    {
        $validated = $request->validated();
        $role->permissions()->sync(array_values($validated["permissions"]));
        return redirect()->route("admin.roles.show", ["role" => $role->id]);
    }
}
