<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterHeadquarterRequest;
use App\Models\Device;
use App\Models\Headquarter;
use Illuminate\Http\Request;

class HeadquarterController extends Controller
{
    public function index()
    {
        $headquarters = Headquarter::all();
        if ($headquarters->isEmpty()) {
            $response = [
                'message' => 'No hay datos disponibles'
            ];
        } else {
            $response = [
                'message' => 'Datos recuperados exitosamente',
                'data' => $headquarters
            ];
        }

        return response()->json($response);
    }

    //Mostrar una unica sede 

    public function show($id)
    {
        $headquarter = Headquarter::find($id);

        if ($headquarter === null) {

            $response = [

                'message' => 'no hay datos disponibles'
            ];
        } else {


            $response = [

                'message' => 'Datos recuperados exitosamente',
                'data' => $headquarter
            ];
        }
        return response()->json($response);
    }

    //registrar un equipo 

    public function register(RegisterHeadquarterRequest $request)
    {
        $headquarter=new Headquarter();
        $headquarter->name=$request->name;
        $headquarter->state=$request->state;
        $headquarter->city=$request->city;
        $headquarter->address=$request->address;
        $headquarter->save();

        $response=[
            'message'=>'Registro creado exitosamente',
            'status'=>201, 
            'data'=> $headquarter
        ];

        return response()->json($response,201);
    }
}
