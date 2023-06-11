<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    /**
    *   ( Muestra todos los estados de dispositivos registrados)
    *
    *   @OA\Schema(
    *       schema="Status1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="operativo"),
    *   )
    *
    *   @OA\Schema(
    *       schema="Status2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="number", example="no operativo"),
    *   )
    *
    *   @OA\Get (
    *       path="/api/status",
    *       tags={"Statuses"},
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
    *                           @OA\Schema(ref="#/components/schemas/Status1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Status2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index()
    {
        $statuses = Status::all();
        if ($statuses->isEmpty()) {
            $response = [
                "message" => "No hay datos disponibles",
                "status" => "200",
            ];
        } else {
            foreach($statuses as $status){
                $data[] = [
                    "id"=>$status->id,
                    "name"=>$status->name
                ];
            }
            $response = [
                "message" => "Datos recuperados exitosamente",
                "status" => "200",
                "data" => $data,
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Muestra datos de un estado de dispositivo identificada por {id})
    *   @OA\Get (
    *       path="/api/status/{id}",
    *       tags={"Statuses"},
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
    *                   @OA\Property(property="id", type="number", example="1"),
    *                   @OA\Property(property="name", type="number", example="operativo"),
    *               )
    *           )
    *       )
    *   )
    */
    public function show($id)
    {
        $status = Status::find($id);
        if ($status === null) {
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200
            ];
        } else {
            $data = [
                "id"=>$status->id,
                "name"=>$status->name,
            ];
            $response = [
                "message"=>"Datos recuperados exitosamente",
                "status"=>200,
                "data" =>$data
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Crea un nuevo estado de un dispositivo)
    *   @OA\Post (
    *       path="/api/status/register",
    *       tags={"Statuses"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="number", example="en mantenimiento"),
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Estado de dispositivo creado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado exitosamente"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="3"),
    *                   @OA\Property(property="name", type="number", example="en mantenimiento"),
    *               )
    *           )
    *       )
    *   )
    */
    public function register(RegisterStatusRequest $request)
    {
        $status = new Status();
        $status->name = $request->name;
        $status->save();
        $response = [
            "message" => "Registro creado exitosamente",
            "status" => 201,
            "data" => [
                "id"=>$status->id,
                "name"=>$status->name
            ]
        ];
        return response()->json($response, 201);
    }

    /**
    *   ( Actualiza los datos de un estado de dispositivo identificado por {id})
    *   @OA\Put(
    *       path="/api/status/update",
    *       tags={"Statuses"},
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
    *                   @OA\Property(property="name", type="number", example="en reparacion"),
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Datos de estado de dispositivo actualizados",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro actualizado exitosamente"),
    *               @OA\Property(property="status", type="integer", example=200),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="3"),
    *                   @OA\Property(property="name", type="number", example="en reparación"),
    *               )
    *           )
    *       )
    *   )
    */
    public function update(UpdateStatusRequest $request, $id)
    {
        $status = Status::find($id);
        if (!$status) {
            $response = [
                "message" => "Registro no existente",
                "status" => 200
            ];
        } else {
            $status->name = $request->name;
            $status->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 200,
                "data" => [
                    "id"=>$status->id,
                    "name"=>$status->name
                ]
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Elimina los datos de un estado identificado por {id})
    *   @OA\Delete(
    *       path="/api/status/{id}",
    *       tags={"Statuses"},
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
    *                   @OA\Property(property="name", type="string", example="en mantenimiento"),
    *               )
    *           )
    *       )
    *   )
    */
    public function destroy(string $id)
    {
        $status = Status::find($id);
        if ($status == null) {
            $data = [
                "message" =>"No existe el estado de dispositivo",
                "status"=>200
            ];
        } else {
            $devices=$status->devices;
            if (!$devices->isEmpty()){
                $data= [
                    "message"=>"El registro no puede ser borrado ya que existen dispositivos con este estado asignado",
                    "status"=>200,
                    "data"=>$devices
                ];
            }else{
                $status_eliminado=[
                    "id"=>$status->id,
                    "name"=>$status->name
                ];
                $status->delete();
                $data =[
                    "message"=>"Datos de estado de dispositivo borrado exitosamente",
                    "status"=>200,
                    "data"=>$status_eliminado
                ];
            }
        }
        return response()->json($data);
    }
}
