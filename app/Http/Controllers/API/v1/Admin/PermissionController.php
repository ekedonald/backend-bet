<?php

namespace App\Http\Controllers\API\v1\Admin;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Requests\Role\CreatePermission;
use App\Services\AppMessages;
use Exception;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::GET_PERMISSION_LIST,
            'permissions' => $permissions
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePermission $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();

        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::CREATE_PERMISSION,
            'permission' => $permission
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::where('id', $id)->first();
        if($permission){
            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::GET_PERMISSION,
                'permission' => $permission
            ], Response::HTTP_OK);
        }else{
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::PERMISSION_NOT_FOUND,
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
    public function update(CreatePermission $request, $id)
    {
        $permission = Permission::where('id', $id)->first();

        if($permission){
            $permission->name = $request->name;
            $permission->save();

            return ResponseController::response(true,[
                ResponseController::MESSAGE => AppMessages::UPDATE_PERMISSION,
                'permission' => $permission
            ], Response::HTTP_OK);
        }else{
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::PERMISSION_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
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
        $permission = Permission::where('id', $id)->first();
        if($permission){
            try {
                $permission->delete();
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::PERMISSION_DELETED
                ], Response::HTTP_OK);
            } catch (Exception $e) {
                report($e);
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => AppMessages::PERMISSION_NOT_DELETED
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }else{
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::PERMISSION_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
