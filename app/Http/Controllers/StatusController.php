<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterStatusRequest;
use Illuminate\Http\Request;
use App\Models\Status;
class StatusController extends Controller
{
    public function index()
    {
//mostrar todos los status
        $statuses = Status::all();

        if ($statuses->isEmpty()) {
            $response = [

                'message' => 'No hay datos disponibles',
            ];
        } else {
            $response = [

                'message' => 'Datos recuperados exitosamente',
                'data' => $statuses
            ];
        }
        return response()->json($response);
    }
//mustrar un status

public function show($id)
{
    $statuses = Status::find($id);

    if ($statuses === null) {

        $response = [
            'message' => 'No hay datos disponibles'
        ];
    } else {

        $response = [
            'menssage' => 'Datos recuperados exitosamente',
            'data' => $statuses
        ];
    }
    return response()->json($response);
}

//registrar un status (equipo)

public function register(RegisterStatusRequest $request)
{
    $statuses = new Status();
    $statuses->name = $request->name;
    $statuses->save();

    $response = [
        'message' => 'Registro creado exitosamente',
        'status' => 201,
        'data' => $statuses
    ];

    return response()->json($response, 201);
}

public function update(RegisterStatusRequest $request, $id)
    {
        $statuses = Status::find($id);
        if (!$statuses) {
            $response = [
                "message" => "Registro no existente",
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

    public function destroy(string $id)
    {
        $statuses = Status::find($id);
        if ($statuses == null) {
            $data = ["message" => "No existe el status solicitado"];
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
