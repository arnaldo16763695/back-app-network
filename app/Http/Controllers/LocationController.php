<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterLocationRequest;
use App\Models\Headquarter;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class LocationController extends Controller
{
    public function index()
    {
        $locations=Location::all();
        if($locations->isEmpty())
        {
        $response= [
            'message'=>'No hay datos disponibles'
        ];
    }else{ 
        $response= [
            'message'=>'Datos recuperados exitosamente',
            'data'=>$locations
        ];
        }

        return response()->json($response);
    }

    //mostrar una locacion en especifico 

    public function show($id)
    {
        $location=Location::find($id);
        if($location===null){

            $response= [
                'message'=>'No hay datos disponibles'
            ];
        }else{

            $response= [
                'message'=>'Datos recuperados exitosamente',
                'data'=>$location
            ];
        }
        return response()->json($response);
    }
//registrar una locacion
    public function register(RegisterLocationRequest $request)
    {
        $location=new Location();
        $location->name=$request->name;
        $location->observation=$request->observation;
        $location->headquarter_id=$request->headquarter_id;
        $location->save();

        $response=[
            'message'=>'Registro creado exitosamente',
            'status'=>201, 
            'data'=> $location
        ];
        
          return response()->json($response,201);

    }
    public function update(RegisterLocationRequest $request, $id)
    {
        $location = Location::find($id);
        $headquarter = Headquarter::find($request->headquarter_id);
        if(!$location||!$headquarter){
            $response=[
                "message"=>"Registro no existe o la sede no existe"
            
            ];
        }else{
            
            $location->name=$request->name;
            $location->observation=$request->observation;
            $location->headquarter_id=$request->headquarter_id;
            $location->save();
            
            $response=[
                "message"=>"registro actualizado exitosamente",
                "status"=>201,
                "data"=>$location
            ];
        }
        return response()->json($response);
    }
}
