<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use App\Models\Headquarter;
use App\Models\Location;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class DeviceController extends Controller
{

    /**
    *   ( Muestra todos los dispositivos registrados )
    *
    *   @OA\Schema(
    *       schema="Device1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="Switch No.1"),
    *       @OA\Property(property="manufacturer", type="string", example="cisco"),
    *       @OA\Property(property="model", type="string", example="C3560g"),
    *       @OA\Property(property="serial", type="string", example="SN1234567823ASD324"),
    *       @OA\Property(property="code", type="string", example="VIT-SW001"),
    *       @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *       @OA\Property(property="description", type="string", example="Switch 48 puertos 10/100/1000 Poe"),
    *       @OA\Property(
    *           property="location",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="1"),
    *           @OA\Property(property="name", type="string", example="Sala de Servidores"),
    *           @OA\Property(property="observation", type="string", example="Próximo mantenimiento Agosto 2023"),
    *           @OA\Property(
    *               property="headquarter",
    *               type="object",
    *               @OA\Property(property="id", type="number", example="1"),
    *               @OA\Property(property="name", type="string", example="Sede Vit Paraguaná"),
    *               @OA\Property(property="state", type="string", example="Falcón"),
    *               @OA\Property(property="city", type="string", example="Punto Fijo"),
    *               @OA\Property(property="address", type="string", example="Zona Franca"),
    *           ),
    *       ),
    *       @OA\Property(
    *           property="status",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="1"),
    *           @OA\Property(property="name", type="string", example="activo"),
    *       ),
    *       @OA\Property(
    *           property="type",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="2"),
    *           @OA\Property(property="name", type="string", example="switch"),
    *       ),
    *   )
    *
    *   @OA\Schema(
    *       schema="Device2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="string", example="Router "),
    *       @OA\Property(property="manufacturer", type="string", example="tp-link"),
    *       @OA\Property(property="model", type="string", example="AC64"),
    *       @OA\Property(property="serial", type="string", example="SNR100000234567823ASD324"),
    *       @OA\Property(property="code", type="string", example="VIT-RT031"),
    *       @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *       @OA\Property(property="description", type="string", example="Router dual band 2.4GHz & 5Ghz"),
    *       @OA\Property(
    *           property="location",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="2"),
    *           @OA\Property(property="name", type="string", example="Switch No.1"),
    *           @OA\Property(property="observation", type="string", example="Switch No.1"),
    *           @OA\Property(
    *               property="headquarter",
    *               type="object",
    *               @OA\Property(property="id", type="number", example="2"),
    *               @OA\Property(property="name", type="string", example="Switch No.1"),
    *               @OA\Property(property="state", type="string", example="Switch No.1"),
    *               @OA\Property(property="city", type="string", example="Switch No.1"),
    *               @OA\Property(property="address", type="string", example="Switch No.1"),
    *           ),
    *       ),
    *       @OA\Property(
    *           property="status",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="2"),
    *           @OA\Property(property="name", type="string", example="Switch No.1"),
    *       ),
    *       @OA\Property(
    *           property="type",
    *           type="object",
    *           @OA\Property(property="id", type="number", example="3"),
    *           @OA\Property(property="name", type="string", example="router"),
    *       ),
    *   )
    *
    *   @OA\Get (
    *       path="/api/devices",
    *       tags={"Devices"},
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
    *                           @OA\Schema(ref="#/components/schemas/Device1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/Device2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index()
    {
        $devices = Device::all();
        if ($devices->isEmpty()) {
            $response = [
                'message' => 'No hay datos disponibles',
                'status'=>"200"
            ];
        } else {
            foreach($devices as $device){
                $headquarter=[
                    "id"=>$device->location->headquarter->id,
                    "name"=>$device->location->headquarter->name,
                    "state"=>$device->location->headquarter->state,
                    "city"=>$device->location->headquarter->city,
                    "address"=>$device->location->headquarter->address,
                ];
                $location=[
                    "id"=>$device->location->id,
                    "name"=>$device->location->name,
                    "observation"=>$device->location->observation,
                    "headquarter"=>$headquarter
                ];
                $type = [
                    "id"=>$device->type->id,
                    "name"=>$device->type->name,
                ];
                $status = [
                    "id"=>$device->status->id,
                    "name"=>$device->status->name,
                ];
                $data[]=[
                    "id"=>$device->id,
                    "name"=>$device->name,
                    "manufacturer"=>$device->manufacturer,
                    "model"=>$device->model,
                    "serial"=>$device->serial,
                    "code"=>$device->code,
                    "observation"=>$device->observation,
                    "description"=>$device->description,
                    // "location_id"=>$device->location_id,
                    "location"=>$location,
                    // "status_id"=>$device->status_id,
                    "status"=>$status,
                    // "type_id"=>$device->status_id,
                    "type"=>$type,
                ];
            }
            $response = [
                'message' => 'Datos recuperados exitosamente',
                'status'=>"200",
                'data' => $data
            ];
        }
        return response()->json($response,200);
    }

    /**
    *   ( Mostrar datos de un unico equipo seleccionado por id)
    *   @OA\Get (
    *       path="/api/devices/{id}",
    *       tags={"Devices"},
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
    *                   type="array",
    *                   property="data",
    *                   @OA\Items(
    *                       anyOf={
    *                           @OA\Schema(ref="#/components/schemas/Device2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    **/
    public function show($id)
    {
        $device = Device::find($id);
        if ($device === null) {
            $response = [
                'message' => 'No hay datos disponibles',
                'status'=>"200"
            ];
        } else {
            $headquarter=[
                "id"=>$device->location->headquarter->id,
                "name"=>$device->location->headquarter->name,
                "state"=>$device->location->headquarter->state,
                "city"=>$device->location->headquarter->city,
                "address"=>$device->location->headquarter->address,
            ];
            $location=[
                "id"=>$device->location->id,
                "name"=>$device->location->name,
                "observation"=>$device->location->observation,
                "headquarter"=>$headquarter
            ];
            $type = [
                "id"=>$device->type->id,
                "name"=>$device->type->name,
            ];
            $status = [
                "id"=>$device->status->id,
                "name"=>$device->status->name,
            ];
            $data[]=[
                "id"=>$device->id,
                "name"=>$device->name,
                "manufacturer"=>$device->manufacturer,
                "model"=>$device->model,
                "serial"=>$device->serial,
                "code"=>$device->code,
                "observation"=>$device->observation,
                "description"=>$device->description,
                // "location_id"=>$device->location_id,
                "location"=>$location,
                // "status_id"=>$device->status_id,
                "status"=>$status,
                // "type_id"=>$device->status_id,
                "type"=>$type
            ];
            $response = [
                'menssage' => 'Datos recuperados exitosamente',
                'status'=>"200",
                'data' => $data
            ];
        }
        return response()->json($response,200);
    }

    /**
    *   ( Crea un nuevo dispositivo)
    *   @OA\Post (
    *       path="/api/devices/register",
    *       tags={"Devices"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="string", example="Router "),
    *                   @OA\Property(property="manufacturer", type="string", example="tp-link"),
    *                   @OA\Property(property="model", type="string", example="AC64"),
    *                   @OA\Property(property="serial", type="string", example="SNR100000234567823ASD324"),
    *                   @OA\Property(property="code", type="string", example="VIT-RT031"),
    *                   @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *                   @OA\Property(property="description", type="string", example="Router dual band 2.4GHz & 5Ghz"),
    *                   @OA\Property(property="type_id", type="number", example="1"),
    *                   @OA\Property(property="status_id", type="number", example="1"),
    *                   @OA\Property(property="location_id", type="number", example="1")
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Dispositivo Creado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="user",
    *                   type="object",
    *                   @OA\Property(property="name", type="string", example="Router "),
    *                   @OA\Property(property="manufacturer", type="string", example="tp-link"),
    *                   @OA\Property(property="model", type="string", example="AC64"),
    *                   @OA\Property(property="serial", type="string", example="SNR100000234567823ASD324"),
    *                   @OA\Property(property="code", type="string", example="VIT-RT031"),
    *                   @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *                   @OA\Property(property="description", type="string", example="Router dual band 2.4GHz & 5Ghz"),
    *                   @OA\Property(property="status_id", type="number", example="1"),
    *                   @OA\Property(property="location_id", type="number", example="1"),
    *                   @OA\Property(property="type_id", type="number", example="1"),
    *                   @OA\Property(property="created_at", type="number", example="2023-06-10T22:27:14.000000"),
    *                   @OA\Property(property="updated_at", type="number", example="2023-06-10T22:27:14.000000"),
    *                   @OA\Property(property="id", type="number", example="2")
    *               )
    *           )
    *       )
    *   )
    */
    public function register(RegisterDeviceRequest $request)
    {
        $device = new Device();
        $device->name = clean_extra_spaces($request->name);
        $device->manufacturer = $request->manufacturer;
        $device->model = $request->model;
        $device->serial = ($request->serial);
        $device->code = $request->code;
        $device->observation = $request->observation;
        $device->description = $request->description;
        $device->status_id = $request->status_id;
        $device->location_id = $request->location_id;
        $device->type_id = $request->type_id;
        $device->save();

        $response = [
            'message' => 'Registro creado exitosamente',
            'status' => 201,
            'data' => $device
        ];

        return response()->json($response, 201);
    }

    /**
    *   ( Actualiza los datos de un dispositivo seleccionado por {id})
    *   @OA\Put (
    *       path="/api/devices/{id}",
    *       tags={"Devices"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(property="name", type="string", example="Router No.1"),
    *                   @OA\Property(property="manufacturer", type="string", example="tp-link"),
    *                   @OA\Property(property="model", type="string", example="AC64"),
    *                   @OA\Property(property="serial", type="string", example="SNR100000234567823ASD324"),
    *                   @OA\Property(property="code", type="string", example="VIT-RT031"),
    *                   @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *                   @OA\Property(property="description", type="string", example="Router dual band 2.4GHz & 5Ghz"),
    *                   @OA\Property(property="type_id", type="number", example="1"),
    *                   @OA\Property(property="status_id", type="number", example="1"),
    *                   @OA\Property(property="location_id", type="number", example="1")
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *           response=201,
    *           description="Registro actualizado exitosamente",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="string", example="Router No.1"),
    *                   @OA\Property(property="name", type="string", example="Router No.1"),
    *                   @OA\Property(property="manufacturer", type="string", example="tp-link"),
    *                   @OA\Property(property="model", type="string", example="AC64"),
    *                   @OA\Property(property="serial", type="string", example="SNR100000234567823ASD324"),
    *                   @OA\Property(property="code", type="string", example="VIT-RT031"),
    *                   @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *                   @OA\Property(property="description", type="string", example="Router dual band 2.4GHz & 5Ghz"),
    *                   @OA\Property(property="location_id", type="number", example="1"),
    *                   @OA\Property(property="created_at", type="number", example="2023-06-10T22:43:53.000000Z"),
    *                   @OA\Property(property="updated_at", type="number", example="2023-06-10T22:43:53.000000Z"),
    *                   @OA\Property(property="status_id", type="number", example="1"),
    *                   @OA\Property(property="type_id", type="number", example="1"),
    *               )
    *           )
    *       )
    *   )
    */
    public function update(UpdateDeviceRequest $request, $id)
    {
        $device = Device::find($id);
        $location = Location::find($request->location_id);
        if (!$device || !$location) {
            $response = [
                "message" => "Registro no existente",
            ];
        } else {
            $device->name = $request->name;
            $device->manufacturer = $request->manufacturer;
            $device->model = $request->model;
            $device->serial = $request->serial;
            $device->code = $request->code;
            $device->observation = $request->observation;
            $device->description = $request->description;
            $device->type_id = $request->type_id;
            $device->status_id = $request->status_id;
            $device->location_id = $request->location_id;
            $device->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 201,
                "data" => $device
            ];
        }
        return response()->json($response,201);
    }

    /**
    *   ( Elimina los datos de un dispositivo identificado por id )
    *   @OA\Delete(
    *       path="/api/devices/{id}",
    *       tags={"Devices"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Parameter(
    *           in="path",
    *           name="id",
    *           required=true,
    *           @OA\Schema(type="number")
    *       ),
    *
    *       @OA\Response(
    *           response=200,
    *           description="Registro eliminado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Datos de dispositivo borrados exitosamente"),
    *               @OA\Property(property="status", type="string", example="200"),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id", type="number", example="1"),
    *                   @OA\Property(property="name", type="string", example="Router No.1"),
    *                   @OA\Property(property="manufacturer", type="string", example="tp-link"),
    *                   @OA\Property(property="model", type="string", example="AC64"),
    *                   @OA\Property(property="serial", type="string", example="SNR100000234567823ASD324"),
    *                   @OA\Property(property="code", type="string", example="VIT-RT031"),
    *                   @OA\Property(property="observation", type="string", example="adquirido en 2018"),
    *                   @OA\Property(property="description", type="string", example="Router dual band 2.4GHz & 5Ghz"),
    *                   @OA\Property(property="location_id", type="number", example="1"),
    *                   @OA\Property(property="created_at", type="number", example="2023-06-10T22:43:53.000000Z"),
    *                   @OA\Property(property="updated_at", type="number", example="2023-06-10T22:43:53.000000Z"),
    *                   @OA\Property(property="status_id", type="number", example="1"),
    *                   @OA\Property(property="type_id", type="number", example="1"),
    *             )
    *         )
    *     )
    *
    * )
    */
    public function destroy(string $id)
    {
        $device = Device::find($id);
        if ($device == null) {
            $data = [
                "message" => "No existe el dispositivo solicitado",
                'status'=>"200"
            ];
        } else {
            $device->delete();

            $data =[
                "message"=>"Datos de dispositivo borrados exitosamente",
                'status'=>"200",
                "data"=>$device
            ];
        }
        return response()->json($data,200);
    }
}
