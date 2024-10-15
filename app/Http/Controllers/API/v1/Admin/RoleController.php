<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Requests\Role\AddRoleToUser;
use App\Http\Requests\Role\CreateRole;
use App\Models\User;
use App\Services\AppMessages;
use Exception;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $roles = Role::with('permissions:name')->get();
        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::GET_ROLE_LIST,
            'roles' => $roles
        ], Response::HTTP_OK);
    }

    public function addRoleToUser(AddRoleToUser $request){
        try {
            $user = User::findOrFail($request->user_id);
            $user->assignRole($request->role);
            $user->save();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::ADD_ROLE_TO_USER,
                'user' => $user,
            ], Response::HTTP_OK);
        } catch (RoleDoesNotExist $e) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRole $request)
    {
        try {
            $role = new Role();
            $role->name = $request->name;
            $role->save();

            if($request->has("permissions")){
                $role->syncPermissions($request->permissions);
            }

            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::CREATE_ROLE,
                'role' => $role
            ], Response::HTTP_OK);
        } catch (PermissionDoesNotExist $e) {
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('permissions:name')->where('id', $id)->first();
        if($role){
            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::GET_ROLE,
                'role' => $role
            ], Response::HTTP_OK);
        }else{
            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::ROLE_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRole $request, $id)
    {
        try {
            $role = Role::where('id', $id)->first();
            if(!$role){
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::ROLE_NOT_FOUND,
                ], Response::HTTP_NOT_FOUND);
            }
            $role->name = $request->name;
            $role->save();

            if($request->has("permissions")){
                $role->syncPermissions($request->permissions);
            }

            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::UPDATE_ROLE,
                'role' => $role
            ], Response::HTTP_OK);

        } catch (PermissionDoesNotExist $e) {
            report($e);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::where('id', $id)->first();
        if($role){
            try {
                $role->delete();
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::ROLE_DELETED
                ], Response::HTTP_OK);
            } catch (Exception $e) {
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }else{
            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::ROLE_NOT_DELETED
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
