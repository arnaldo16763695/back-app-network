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
    $status = Status::find($id);

    if ($status === null) {

        $response = [
            'message' => 'No hay datos disponibles'
        ];
    } else {

        $response = [
            'menssage' => 'Datos recuperados exitosamente',
            'data' => $status
        ];
    }
    return response()->json($response);
}

//registrar un status (equipo)

public function register(RegisterStatusRequest $request)
{
    $status = new Status();
    $status->name = $request->name;
    $status->save();

    $response = [
        'message' => 'Registro creado exitosamente',
        'status' => 201,
        'data' => $status
    ];

    return response()->json($response, 201);
}

}
