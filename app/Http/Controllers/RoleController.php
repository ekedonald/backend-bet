<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function index()
    {
        $roles = $this->role::paginate(20);

        return view("roles.index", ['roles' => $roles]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("roles.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name'
        ]);

        $this->role->create([
            'name' => $request->name
        ]);

        return redirect()->route('role.index')->with('success', 'Role Created');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $role = $this->role->where('id', $id)->first();
        if(!$role){
            return redirect()->route('role.index')->with('error', 'Role does not exist');
        }
        return view('roles.edit', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' =>  ['required', Rule::unique('roles')->ignore($id)]
        ]);

        $role = $this->role->where('id', $id)->first();
        if(!$role){
            return redirect()->route('role.index')->with('error', 'Role does not exist');
        
        }
        $role->name = $request->name;
        $role->save();
        return redirect()->route('role.index')->with('success', 'Role has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->role->where('id', $id)->first();
        if(!$role){
            return redirect()->route('role.index')->with('error', 'Role does not exist');
        
        }

        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role has been removed');
    }
}