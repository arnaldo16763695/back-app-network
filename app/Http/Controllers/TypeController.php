<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use Illuminate\Http\Request;
use App\Models\Type;
class TypeController extends Controller
{
    /**
    *   ( Muestra todos los tipos de dispositivos registrados)
    *
    *   @OA\Schema(
    *       schema="Type1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="servidor blade"),
    *   )
    *
    *   @OA\Schema(
    *       schema="Type2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="number", example="switch"),
    *   )
    *
    *   @OA\Get (
    *       path="/api/types",
    *       tags={"Types"},
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
    *                           @OA\Schema(ref="#/components/schemas/Type1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Type2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index()
    {
        $types = Type::all();
        if ($types->isEmpty()) {
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200
            ];
        } else {
            foreach($types as $type){
                $data[] = [
                    "id"=>$type->id,
                    "name"=>$type->name,
                ];
            }
            $response = [
                "message" => "Datos recuperados exitosamente",
                "status"=>200,
                "data" => $data
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Muestra datos de un tipo de dispositivo identificado por {id})
    *   @OA\Get (
    *       path="/api/types/{id}",
    *       tags={"Types"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Parameter(
    *           in="path",
    *           name="id",
    *           required=true,
    *           @OA\Schema(type="number")
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="Datos recuperados exitosamente",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Datos recuperados exitosamente"),
    *               @OA\Property(property="status", type="number", example="200"),
    *               @OA\Property(
    *                   type="object",
    *                   property="data",
    *                   @OA\Property(property="id", type="number", example="3"),
    *                   @OA\Property(property="name", type="number", example="router"),
    *               )
    *           )
    *       )
    *   )
    */
    public function show($id)
    {
        $type = Type::find($id);
        if ($type === null) {
            $response = [
                "message" => "No hay datos disponibles",
                "status"=>200
            ];
        } else {
            $data = [
                "id"=>$type->id,
                "name"=>$type->name
            ];
            $response = [
                "menssage" => "Datos recuperados exitosamente",
                "status"=>200,
                "data" => $data
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Crea un nuevo tipo de dispositivo)
    *   @OA\Post (
    *       path="/api/types/register",
    *       tags={"Types"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="number", example="switch"),
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Tipo de dispositivo creado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado exitosamente"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="3"),
    *                   @OA\Property(property="name", type="number", example="switch"),
    *               )
    *           )
    *       )
    *   )
    */
    public function register(RegisterTypeRequest $request)
    {
        $type = new Type();
        $type->name = $request->name;
        $type->save();
        $response = [
            "message" => "Registro creado exitosamente",
            "status" => 201,
            "data" => [
                "id"=>$type->id,
                "name"=>$type->name
            ]
        ];
        return response()->json($response, 201);
    }

    /**
    *   ( Actualiza los datos de un tipo de dispositivo identificado por {id})
    *   @OA\Put(
    *       path="/api/types/{id}",
    *       tags={"Types"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Parameter(
    *           in="path",
    *           name="id",
    *           required=true,
    *           @OA\Schema(type="number")
    *       ),
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="number", example="router"),
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Datos de tipo de dispositivo actualizados",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro actualizado exitosamente"),
    *               @OA\Property(property="status", type="integer", example=200),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="3"),
    *                   @OA\Property(property="name", type="number", example="router"),
    *               )
    *           )
    *       )
    *   )
    */
    public function update(UpdateTypeRequest $request, $id)
    {
        $type = Type::find($id);
        if (!$type) {
            $response = [
                "message" => "Registro no existente",
                "status"=>200
            ];
        } else {
            $type->name = $request->name;
            $type->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 200,
                "data" => [
                    "id"=>$type->id,
                    "name"=>$type->name
                ]
            ];
        }
        return response()->json($response,200);
    }

    /**
    *   ( Elimina los datos de un tipo (type) identificado por {id})
    *   @OA\Delete(
    *       path="/api/types/{id}",
    *       tags={"Types"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Parameter(
    *           in="path",
    *           name="id",
    *           required=true,
    *           @OA\Schema(type="number")
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="Registro eliminado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Datos de estado de dispositivo borrados exitosamente"),
    *               @OA\Property(property="status", type="string", example="200"),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="3"),
    *                   @OA\Property(property="name", type="string", example="switch"),
    *               )
    *           )
    *       )
    *   )
    */
    public function destroy(string $id)
    {
        $type = Type::find($id);
        if ($type == null) {
            $data = [
                "message" => "No existe el equipo solicitado",
                "status"=>200
            ];
        } else {
            $devices = $type->devices;
            if (!$devices->isEmpty()){
                $data= [
                    "message"=>"El registro no puede ser borrado ya que existen dispositivos con este tipo asignado",
                    "status"=>200,
                    "data"=>$devices
                ];
            }else{
                $type_eliminado=[
                    "id"=>$type->id,
                    "name"=>$type->name
                ];
                $type->delete();
                $data =[
                    "message"=>"Datos de tipo de equipo borrado exitosamente",
                    "status"=>200,
                    "data"=>$type_eliminado
                ];
            }
        }
        return response()->json($data);
    }
}
