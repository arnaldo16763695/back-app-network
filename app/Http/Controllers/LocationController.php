<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Headquarter;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class LocationController extends Controller
{
    /**
    *   ( Muestra todas las locaciones registradas )
    *
    *   @OA\Schema(
    *       schema="Location1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="number", example="Servidores 1"),
    *       @OA\Property(property="observation", type="number", example="sin observaciones"),
    *       @OA\Property(
    *           property="headquarter",
    *           @OA\Property(property="id", type="number", example="1"),
    *           @OA\Property(property="name", type="string", example="Planta"),
    *           @OA\Property(property="state", type="number", example="Falcón"),
    *           @OA\Property(property="city", type="number", example="Punto Fijo"),
    *           @OA\Property(property="address", type="number", example="Zona Frana")
    *       ),
    *   )
    *
    *   @OA\Schema(
    *       schema="Location2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="string", example="Soporte Técnico"),
    *       @OA\Property(property="observation", type="number", example="sin observaciones"),
    *       @OA\Property(
    *           property="headquarter",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="2"),
    *           @OA\Property(property="name", type="number", example="Caracas"),
    *           @OA\Property(property="state", type="number", example="Distrito Capital"),
    *           @OA\Property(property="city", type="number", example="Caracas"),
    *           @OA\Property(property="address", type="number", example="Chacao")
    *       ),
    *   )
    *
    *   @OA\Schema(
    *       schema="Location3Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="Servidores"),
    *   )
    *
    *   @OA\Schema(
    *       schema="Location4Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="string", example="Soporte Técnico"),
    *   )
    *
    *   @OA\Get (
    *       path="/api/locations",
    *       tags={"Locations"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Response(
    *           response=200,
    *           description="Datos recuperados exitosamente",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Datos recuperados exitosamente"),
    *               @OA\Property(property="status", type="number", example="200"),
    *
    *               @OA\Property(
    *                   type="array",
    *                   property="data",
    *                   @OA\Items(
    *                       anyOf={
    *                           @OA\Schema(ref="#/components/schemas/Location1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Location2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index()
    {
        $locations=Location::all();
        foreach($locations as $location){
            $headquarter= [
                "id"=>$location->headquarter->id,
                "name"=>$location->headquarter->name,
                "state"=>$location->headquarter->state,
                "city"=>$location->headquarter->city,
                "address"=>$location->headquarter->address
            ];
            $data[]=[
                "id"=>$location->id,
                "name"=>$location->name,
                "observation"=>$location->observation,
                "headquarter"=>$headquarter
            ];
        }
        if($locations->isEmpty())
        {
        $response= [
            'message'=>'No hay datos disponibles',
            'status'=>200
        ];
    }else{
        $response= [
            'message'=>'Datos recuperados exitosamente',
            'status'=>200,
            'data'=>$data
        ];
        }
        return response()->json($response,200);
    }

    /**
    *   ( Muestra una locación identificada por {id})
    *   @OA\Get (
    *       path="/api/locations/{id}",
    *       tags={"Locations"},
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
    *                   @OA\Property(property="id", type="number", example="2"),
    *                   @OA\Property(property="name", type="string", example="Soporte Técnico"),
    *                   @OA\Property(property="observation", type="number", example="sin observaciones"),
    *                   @OA\Property(
    *                       property="headquarter",
    *                       type="object",
    *                       @OA\Property(property="id", type="number", example="2"),
    *                       @OA\Property(property="name", type="number", example="Caracas"),
    *                       @OA\Property(property="state", type="number", example="Distrito Capital"),
    *                       @OA\Property(property="city", type="number", example="Caracas"),
    *                       @OA\Property(property="address", type="number", example="Chacao")
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function show($id)
    {
        $location=Location::find($id);
        if($location===null){

            $response= [
                'message'=>'No hay datos disponibles',
                'status'=>'200'
            ];
        }else{
            $data =[
                "id"=>$location->id,
                "name"=>$location->name,
                "observation"=>$location->observation,
                "headquarter"=>[
                    "id"=>$location->headquarter->id,
                    "name"=>$location->headquarter->name,
                    "state"=>$location->headquarter->state,
                    "city"=>$location->headquarter->city,
                    "address"=>$location->headquarter->address
                ]
            ];
            $response= [
                'message'=>'Datos recuperados exitosamente',
                'status'=>'200',
                'data'=>$data
            ];
        }
        return response()->json($response);
    }


    /**
    *   ( Crea una nueva locación)
    *   @OA\Post (
    *       path="/api/locations/register",
    *       tags={"Locations"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="string", example="Servidores"),
    *                   @OA\Property(property="observation", type="string", example="sin comentarios"),
    *                   @OA\Property(property="headquarter_id", type="number", example="1")
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Location Creada",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="1"),
    *                   @OA\Property(property="name", type="string", example="Servidores"),
    *                   @OA\Property(property="observation", type="string", example="sin comentarios"),
    *                   @OA\Property(
    *                       property="headquarter",
    *                       type="object",
    *                       @OA\Property(property="id", type="string", example="1"),
    *                       @OA\Property(property="name", type="string", example="Planta"),
    *                       @OA\Property(property="state", type="string", example="Falcón"),
    *                       @OA\Property(property="city", type="string", example="Punto Fijo"),
    *                       @OA\Property(property="address", type="string", example="Zona Franca"),
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function register(RegisterLocationRequest $request)
    {
        $location=new Location();
        $location->name=$request->name;
        $location->observation=$request->observation;
        $location->headquarter_id=$request->headquarter_id;
        $location->save();
        $data=[
            "id"=>$location->id,
            "name"=>$location->name,
            "observation"=>$location->observation,
            "headquarter"=>[
                "id"=>$location->headquarter->id,
                "name"=>$location->headquarter->name,
                "state"=>$location->headquarter->state,
                "city"=>$location->headquarter->city,
                "address"=>$location->headquarter->address,
            ]
        ];
        $response=[
            'message'=>'Registro creado exitosamente',
            'status'=>201,
            'data'=> $data
        ];

        return response()->json($response,201);

    }

    /**
    *   ( Actualizar datos de una locación identificada por {id})
    *   @OA\Put (
    *       path="/api/locations/{id}",
    *       tags={"Locations"},
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
    *                   @OA\Property(property="name", type="string", example="Servidores"),
    *                   @OA\Property(property="observation", type="string", example="sin comentarios"),
    *                   @OA\Property(property="headquarter_id", type="number", example="1")
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Location Creada",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="1"),
    *                   @OA\Property(property="name", type="string", example="Servidores"),
    *                   @OA\Property(property="observation", type="string", example="sin comentarios"),
    *                   @OA\Property(
    *                       property="headquarter",
    *                       type="object",
    *                       @OA\Property(property="id", type="string", example="1"),
    *                       @OA\Property(property="name", type="string", example="Planta"),
    *                       @OA\Property(property="state", type="string", example="Falcón"),
    *                       @OA\Property(property="city", type="string", example="Punto Fijo"),
    *                       @OA\Property(property="address", type="string", example="Zona Franca"),
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function update(UpdateLocationRequest $request, $id)
    {
        $location = Location::find($id);
        if(!$location){
            $response=[
                "message"=>"La locación no existe"
            ];
        }else{
            $location->name=$request->name;
            $location->observation=$request->observation;
            $location->headquarter_id=$request->headquarter_id;
            $location->save();
            $data =[
                "id"=>$location->id,
                "name"=>$location->name,
                "observation"=>$location->observation,
                "headquarter"=>[
                    "id"=>$location->headquarter->id,
                    "name"=>$location->headquarter->name,
                    "state"=>$location->headquarter->state,
                    "city"=>$location->headquarter->city,
                    "address"=>$location->headquarter->address,
                ]
            ];
            $response=[
                "message"=>"registro actualizado exitosamente",
                "status"=>201,
                "data"=>$data
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Elimina los datos de una locación identificada por {id})
    *   @OA\Delete(
    *       path="/api/locations/{id}",
    *       tags={"Locations"},
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
    *               @OA\Property(property="message", type="string", example="Datos de locación borrados exitosamente"),
    *               @OA\Property(property="status", type="string", example="200"),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="number", example="1"),
    *                   @OA\Property(property="name", type="string", example="Servidores 1"),
    *                   @OA\Property(property="observation", type="string", example="sin observaciones"),
    *                   @OA\Property(
    *                       property="headquarter",
    *                       type="object",
    *                       @OA\Property(property="id", type="string", example="1"),
    *                       @OA\Property(property="name", type="string", example="Planta"),
    *                       @OA\Property(property="state", type="string", example="Falcón"),
    *                       @OA\Property(property="city", type="string", example="Punto Fijo"),
    *                       @OA\Property(property="address", type="string", example="Zona Franca"),
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function destroy(string $id)
    {
        $location = Location::find($id);
        if (!$location){
            $response= [
                "message"=>"No existe la locacion que quiere eliminar",
            ];
        } else {
            $devices = $location->devices;

            if (!$devices->isEmpty()){
                $response= [
                    "message"=>"El registro no puede ser borrado ya que existen equipos asociados a esta locacion",
                    "status"=>200,
                    "data"=>$devices
                ];
            }else{
                $data = [
                    "id"=>$location->id,
                    "name"=>$location->name,
                    "observation"=>$location->observation,
                    "headquarter"=>[
                        "id"=>$location->headquarter->id,
                        "name"=>$location->headquarter->name,
                        "state"=>$location->headquarter->state,
                        "city"=>$location->headquarter->city,
                        "address"=>$location->headquarter->address,
                    ]
                ];
                $location->delete();
                $response= [
                    "message"=>"El registro se elimino correctamente",
                    "status"=>200,
                    "data"=>$data
                ];
            }
        }
        return response()->json($response);
    }

    /**
    *   ( Recupera las locaciones de una sede identificada por {id})
    *   @OA\Get (
    *       path="/api/locHead/{id}",
    *       tags={"Locations"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Parameter(
    *           in="path",
    *           name="id",
    *           required=true,
    *           @OA\Schema(type="number")
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="Locaciones de sede recuperadas",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Locaciones recuperados exitosamente"),
    *               @OA\Property(property="status", type="number", example="200"),
    *               @OA\Property(
    *                   type="array",
    *                   property="locations",
    *                   @OA\Items(
    *                       anyOf={
    *                           @OA\Schema(ref="#/components/schemas/Location3Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Location4Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function locHead($id) {
        $headquarter=Headquarter::find($id);
        if ($headquarter===null){
            $response = [
                "message"=>"La sede seleccionada no existe",
                "status"=>200
            ];
        }else{
            $locations=Location::select('id','name')->where('headquarter_id',$id)->get();
            $response=[
                "message"=>"Locaciones recuperadas exitosamente",
                "status"=>200,
                "locations"=>$locations
            ];
        }
        return response()->json($response, 200);
    }

}
