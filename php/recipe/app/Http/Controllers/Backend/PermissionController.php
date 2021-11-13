<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\PermissionStoreRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisssions = Permission::orderByDesc('id')->paginate(20);
        return view("backend.permissions.list", ["permissions" => $permisssions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("backend.permissions.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionStoreRequest $request)
    {
        $validated = $request->validated();
        $active = $request->active ? true : false;
        Permission::create([
            "title" => $validated["title"],
            "slug" => $validated["title"],
            "description" => $validated["description"],
            "active" => $active
        ]);

        return redirect()->route("admin.permissions");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Backend\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Backend\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view("backend.permissions.edit", ["permission" => $permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Backend\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionStoreRequest $request, Permission $permission)
    {
        $validated = $request->validated();
        $active = $request->active ? true : false;

        $permission->title = $validated["title"];
        $permission->slug = $validated["title"];
        $permission->description = $validated["description"];
        $permission->active = $active;
        $permission->save();
        return redirect()->route("admin.permissions");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backend\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function delete(Permission $permission)
    {
        return view("backend.permissions.delete", ['permission' => $permission]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backend\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route("admin.permissions");
    }
}
