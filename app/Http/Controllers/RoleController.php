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
                "status"=>204,
            ];
            return response()->json($response, 204);
        } else {
            $response = [
                "message"=>"Datos recuperados exitosamente",
                "status"=>200,
                "roles"=>$roles
            ];
            return response()->json($response, 200);
        }
    }
}
