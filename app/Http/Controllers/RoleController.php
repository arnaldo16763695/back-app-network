<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();
        if ($roles->isEmpty()){
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200,
            ];
            return response()->json($response, 204);
        } else {
            foreach($roles as $role){
                $data[]=[
                    "id"=>$role->id,
                    "name"=>$role->name,
                ];
            }
            $response = [
                "message"=>"Datos recuperados exitosamente",
                "status"=>200,
                "roles"=>$data
            ];
            return response()->json($response, 200);
        }
    }
}
