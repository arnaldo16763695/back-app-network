<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterTypeRequest;
use Illuminate\Http\Request;
use App\Models\Type;
class TypeController extends Controller
{
    public function index()
    {
//mostrar todos los tipos de equipos (type)
        $types = Type::all();

        if ($types->isEmpty()) {
            $response = [

                'message' => 'No hay datos disponibles',
            ];
        } else {
            $response = [

                'message' => 'Datos recuperados exitosamente',
                'data' => $types
            ];
        }
        return response()->json($response);
    }
//mostrar un equipo (type)

public function show($id)
{
    $types = Type::find($id);

    if ($types === null) {

        $response = [
            'message' => 'No hay datos disponibles'
        ];
    } else {

        $response = [
            'menssage' => 'Datos recuperados exitosamente',
            'data' => $types
        ];
    }
    return response()->json($response);
}

//registrar un type (equipo)

public function register(RegisterTypeRequest $request)
{
    $types = new Type();
    $types->name = $request->name;
    $types->save();

    $response = [
        'message' => 'Registro creado exitosamente',
        'status' => 201,
        'data' => $types
    ];

    return response()->json($response, 201);
}

//actualizar un equipo (type)
public function update(RegisterTypeRequest $request, $id)
    {
        $types = Type::find($id);
        if (!$types) {
            $response = [
                "message" => "Registro no existente",
            ];
        } else {

            $types->name = $request->name;
            $types->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 201,
                "data" => $types
            ];
        }
        return response()->json($response);
    }

    //eliminar un equipo
    public function destroy(string $id)
    {
        $types = Type::find($id);
        if ($types == null) {
            $data = ["message" => "No existe el equipo solicitado"];
        } else {
            $types->delete();

            $data =[
                "message"=>"Datos de equipo borrado exitosamente",
                "data"=>$types
            ];
        }
        return response()->json($data);
    }
}
