<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterStatusRequest;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    // Mostrar todos los status
    public function index()
    {
        $statuses = Status::all();
        if ($statuses->isEmpty()) {
            $response = [
                "message" => "No hay datos disponibles",
            ];
        } else {
            $response = [
                "message" => "Datos recuperados exitosamente",
                "data" => $statuses
            ];
        }
        return response()->json($response);
    }

    // Mostrar un status particular por {id}
    public function show($id)
    {
        $statuses = Status::find($id);
        if ($statuses === null) {
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200
            ];
        } else {
            $data = [
                "id"=>$statuses->id,
                "name"=>$statuses->name,
            ];
            $response = [
                "menssage"=>"Datos recuperados exitosamente",
                "status"=>200,
                "data" =>$data
            ];
        }
        return response()->json($response);
    }

    //registrar un status de equipo
    public function register(RegisterStatusRequest $request)
    {
        $statuses = new Status();
        $statuses->name = $request->name;
        $statuses->save();
        $response = [
            "message" => "Registro creado exitosamente",
            "status" => 201,
            "data" => $statuses
        ];
        return response()->json($response, 201);
    }

    //actualizar un status de equipo
    public function update(RegisterStatusRequest $request, $id)
    {
        $statuses = Status::find($id);
        if (!$statuses) {
            $response = [
                "message" => "Registro no existente",
                "status" => 200
            ];
        } else {
            $statuses->name = $request->name;
            $statuses->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 201,
                "data" => $statuses
            ];
        }
        return response()->json($response);
    }

    // eliminar un status de equipo
    public function destroy(string $id)
    {
        $statuses = Status::find($id);
        if ($statuses == null) {
            $data = [
                "message" =>"No existe el status solicitado",
                "status"=>200
            ];
        } else {
            $statuses->delete();
            $data =[
                "message"=>"Datos de status borrado exitosamente",
                "data"=>$statuses
            ];
        }
        return response()->json($data);
    }
}
