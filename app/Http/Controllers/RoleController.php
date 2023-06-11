<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
    *   ( Muestra todos los roles registrados)
    *
    *   @OA\Schema(
    *       schema="Role1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="Admin"),
    *   )
    *
    *   @OA\Schema(
    *       schema="Role2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="string", example="Supervisor"),
    *   )
    *
    *   @OA\Schema(
    *       schema="Role3Schema",
    *       @OA\Property(property="id", type="number", example="3"),
    *       @OA\Property(property="name", type="string", example="Usuario"),
    *   )
    *
    *   @OA\Get (
    *       path="/api/auth/roles",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Response(
    *           response=200,
    *           description="Datos recuperados exitosamente",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Datos recuperados exitosamente"),
    *               @OA\Property(property="status", type="number", example="200"),
    *               @OA\Property(
    *                   type="array",
    *                   property="data",
    *                   @OA\Items(
    *                       anyOf={
    *                           @OA\Schema(ref="#/components/schemas/Role1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Role2Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Role3Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index() {
        $roles = Role::all();
        if ($roles->isEmpty()){
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200,
            ];
            return response()->json($response, 200);
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
