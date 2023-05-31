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

    public function Update(RegisterHeadquarterRequest $request, $id)
    {
        $headquarter = Headquarter::find($id);
        if (!$headquarter){
            $response= [
                "message"=>"El registro no existe", 
                "status"=> 400
            ];

            
        }else{
            $headquarter->name= $request->name;
            $headquarter->state= $request->state;
            $headquarter->city= $request->city;
            $headquarter->address= $request->address;
            $headquarter->save();
            
            $response = [
                "message"=>"Registro actualizado exitosamente",
                "status"=> 201,
                "data"=> $headquarter    
            ];
        }
        return response()->json($response); 
    }

    //eliminar un equipo

    public function destroy(string $id)
    {
        $headquarter = Headquarter::find($id);
        if (!$headquarter){
            $response= [
                "message"=>"No existe la sede que quiere eliminar", 
            ];
        } else {
            $locations = $headquarter->locations;
         
            if (!$locations->isEmpty()){
                $response= [
                    "message"=>"El registro no puede ser borrado ya que existen localizaciones asociadas a esta sede",
                    "data"=>$locations 
                ];
            }else{
                $headquarter->delete();
                $response= [
                    "message"=>"El registro se elimino correctamente",
                    "data"=>$headquarter
                ];    
            }
        }
        return response()->json($response);
    }
}