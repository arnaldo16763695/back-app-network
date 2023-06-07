<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterTypeRequest;
use Illuminate\Http\Request;
use App\Models\Type;
class TypeController extends Controller
{
    //mostrar todos los tipos de equipos (type)
    public function index()
    {
        $types = Type::all();
        if ($types->isEmpty()) {
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200
            ];
        } else {
            $data = [
                "id"=>$types->id,
                "name"=>$types->name,
            ];
            $response = [
                "message" => "Datos recuperados exitosamente",
                "status"=>200,
                "data" => $data
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
                "message" => "No hay datos disponibles",
                "status"=>200
            ];
        } else {
            $response = [
                "menssage" => "Datos recuperados exitosamente",
                "status"=>200,
                "type" => $types->name
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
            "message" => "Registro creado exitosamente",
            "status" => 201,
            "data" => $types
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
                "status"=>200
            ];
        } else {
            $types->name = $request->name;
            $types->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 200,
                "data" => $types
            ];
        }
        return response()->json($response,200);
    }

    //eliminar un equipo
    public function destroy(string $id)
    {
        $types = Type::find($id);
        if ($types == null) {
            $data = [
                "message" => "No existe el equipo solicitado",
                "status"=>200
            ];
        } else {
            $types->delete();

            $data =[
                "message"=>"Datos de equipo borrado exitosamente",
                "status"=>200,
                "data"=>$types
            ];
        }
        return response()->json($data);
    }
}
