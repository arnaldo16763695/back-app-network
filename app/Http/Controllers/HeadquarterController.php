<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterHeadquarterRequest;
use App\Http\Requests\UpdateHeadquarterRequest;
use App\Models\Headquarter;

class HeadquarterController extends Controller
{
    /**
    *   ( Muestra todas las locaciones registradas )
    *
    *   @OA\Schema(
    *       schema="Headquarter1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="Planta"),
    *       @OA\Property(property="state", type="number", example="Falcón"),
    *       @OA\Property(property="city", type="number", example="Punto Fijo"),
    *       @OA\Property(property="address", type="number", example="Zona Frana")
    *   )
    *
    *   @OA\Schema(
    *       schema="Headquarter2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="number", example="Caracas"),
    *       @OA\Property(property="state", type="number", example="Distrito Capital"),
    *       @OA\Property(property="city", type="number", example="Caracas"),
    *       @OA\Property(property="address", type="number", example="Chacao")
    *   )
    *
    *   @OA\Get (
    *       path="/api/headquarters",
    *       tags={"Headquarters"},
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
    *                           @OA\Schema(ref="#/components/schemas/Headquarter1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Headquarter2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index()
    {
        $headquarters = Headquarter::all();
        if ($headquarters->isEmpty()) {
            $response = [
                'message' => 'No hay datos disponibles',
                "status"=>"200"
            ];
        } else {
            foreach($headquarters as $headquarter){
                $data[] = [
                    "id"=>$headquarter->id,
                    "name"=>$headquarter->name,
                    "state"=>$headquarter->state,
                    "city"=>$headquarter->city,
                    "address"=>$headquarter->address,
                ];
            }
            $response = [
                'message' => 'Datos recuperados exitosamente',
                "status"=>"200",
                'data' => $data
            ];
        }

        return response()->json($response);
    }

    /**
    *   ( Muestra datos de una sede identificada por {id})
    *   @OA\Get (
    *       path="/api/headquarters/{id}",
    *       tags={"Headquarters"},
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
    *                   @OA\Property(property="name", type="number", example="Caracas"),
    *                   @OA\Property(property="state", type="number", example="Distrito Capital"),
    *                   @OA\Property(property="city", type="number", example="Caracas"),
    *                   @OA\Property(property="address", type="number", example="Chacao")
    *               )
    *           )
    *       )
    *   )
    */
    public function show($id)
    {
        $headquarter = Headquarter::find($id);
        if ($headquarter === null) {
            $response = [
                'message' => 'no hay datos disponibles',
                "status"=>"200"
            ];
        } else {
            $data= [
                "id"=>$headquarter->id,
                "name"=>$headquarter->name,
                "state"=>$headquarter->state,
                "city"=>$headquarter->city,
                "address"=>$headquarter->address,
            ];
            $response = [
                'message' => 'Datos recuperados exitosamente',
                "status"=>"200",
                'data' => $data
            ];
        }
        return response()->json($response);
    }


    /**
    *   ( Crea una nueva sede)
    *   @OA\Post (
    *       path="/api/headquarters/register",
    *       tags={"Headquarters"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="number", example="Caracas"),
    *                   @OA\Property(property="state", type="number", example="Distrito Capital"),
    *                   @OA\Property(property="city", type="number", example="Caracas"),
    *                   @OA\Property(property="address", type="number", example="Chacao")
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Sede Creada",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado exitosamente"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="1"),
    *                   @OA\Property(property="name", type="number", example="Caracas"),
    *                   @OA\Property(property="state", type="number", example="Distrito Capital"),
    *                   @OA\Property(property="city", type="number", example="Caracas"),
    *                   @OA\Property(property="address", type="number", example="Chacao")
    *               )
    *           )
    *       )
    *   )
    */
    public function register(RegisterHeadquarterRequest $request)
    {
        // la funcion clean_extra_spaces() esta definida en app/Helpers/helpers.php
        // elimina espacios duplicados en una cadena
        // de caracteres.
        $request_name = clean_extra_spaces($request->name);
        $headquarters = Headquarter::all();
        foreach($headquarters as $headquarter){
            $headquarter_name =clean_extra_spaces($headquarter->name);
            if (strtoupper($headquarter_name)===strtoupper($request_name)){
                $response = [
                    "message"=>"Lo sentimos, ya existe una sede registrada con el nombre '"
                        .$request->name."'",
                    "status"=>200,
                    "data"=> [
                        "id"=>$headquarter->id,
                        "name"=>$headquarter->name,
                        "state"=>$headquarter->state,
                        "city"=>$headquarter->city,
                        "address"=>$headquarter->address,
                    ]
                ];
                return response()->json($response,200);
            }
        }
        $headquarter=new Headquarter();
        $headquarter->name=clean_extra_spaces($request->name);
        $headquarter->state=$request->state;
        $headquarter->city=$request->city;
        $headquarter->address=$request->address;
        $headquarter->save();
        $data = [
            "id"=>$headquarter->id,
            "name"=>$headquarter->name,
            "state"=>$headquarter->state,
            "city"=>$headquarter->city,
            "address"=>$headquarter->address,
        ];
        $response=[
            'message'=>'Registro creado exitosamente',
            'status'=>201,
            'data'=> $data
        ];
        return response()->json($response,201);
    }

    /**
    *   ( Actualiza los datos de una sede identificada por {id})
    *   @OA\Put(
    *       path="/api/headquarters/update",
    *       tags={"Headquarters"},
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
    *                   @OA\Property(property="name", type="number", example="Caracas"),
    *                   @OA\Property(property="state", type="number", example="Distrito Capital"),
    *                   @OA\Property(property="city", type="number", example="Caracas"),
    *                   @OA\Property(property="address", type="number", example="Chacao")
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Datos de sede actualizados",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro actualizado exitosamente"),
    *               @OA\Property(property="status", type="integer", example=200),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="1"),
    *                   @OA\Property(property="name", type="number", example="Caracas"),
    *                   @OA\Property(property="state", type="number", example="Distrito Capital"),
    *                   @OA\Property(property="city", type="number", example="Caracas"),
    *                   @OA\Property(property="address", type="number", example="Chacao")
    *               )
    *           )
    *       )
    *   )
    */
    public function Update(UpdateHeadquarterRequest $request, $id)
    {
        $headquarters = Headquarter::select('*')
            ->whereNotIn('id',[$id])
            ->get();
        // la funcion clean_extra_spaces() esta definida en app/Helpers/helpers.php
        // elimina espacios duplicados en una cadena
        // de caracteres.
        $request_name = clean_extra_spaces($request->name);
        foreach($headquarters as $headquarter){
            $headquarter_name =clean_extra_spaces($headquarter->name);
            if (strtoupper($request_name)===strtoupper($headquarter_name)){
                $response = [
                    "message"=>"Lo sentimos, ya existe una sede registrada  con el nombre '".$request->name."'",
                    "status"=>200,
                    "data"=> [
                        "id"=>$headquarter->id,
                        "name"=>$headquarter->name,
                        "state"=>$headquarter->state,
                        "city"=>$headquarter->city,
                        "address"=>$headquarter->address,
                    ]
                ];
                return response()->json($response,200);
            }
        }

        $headquarter = Headquarter::find($id);
        if (!$headquarter){
            $response= [
                "message"=>"El registro no existe",
                "status"=> 400
            ];


        }else{
            $headquarter->name= clean_extra_spaces($request->name);
            $headquarter->state= $request->state;
            $headquarter->city= $request->city;
            $headquarter->address= $request->address;
            $headquarter->save();

            $data = [
                "id"=>$headquarter->id,
                "name"=>$headquarter->name,
                "state"=>$headquarter->state,
                "city"=>$headquarter->city,
                "address"=>$headquarter->address,
            ];
            $response = [
                "message"=>"Registro actualizado exitosamente",
                "status"=> 200,
                "data"=> $data
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Elimina los datos de una sede identificada por {id})
    *   @OA\Delete(
    *       path="/api/headquarters/{id}",
    *       tags={"Headquarters"},
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
    *               @OA\Property(property="message", type="string", example="Datos de sede borrados exitosamente"),
    *               @OA\Property(property="status", type="string", example="200"),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="1"),
    *                   @OA\Property(property="name", type="string", example="Planta"),
    *                   @OA\Property(property="state", type="string", example="Falcón"),
    *                   @OA\Property(property="city", type="string", example="Punto Fijo"),
    *                   @OA\Property(property="address", type="string", example="Zona Franca"),
    *               )
    *           )
    *       )
    *   )
    */
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
                $data = [
                    "id"=>$headquarter->id,
                    "name"=>$headquarter->name,
                    "state"=>$headquarter->state,
                    "city"=>$headquarter->city,
                    "address"=>$headquarter->address,
                ];
                $headquarter->delete();
                $response= [
                    "message"=>"El registro se elimino correctamente",
                    "data"=>$data
                ];
            }
        }
        return response()->json($response);
    }
}
