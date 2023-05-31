<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterDeviceRequest;
use App\Models\Device;
use App\Models\Headquarter;
use App\Models\Location;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();

        if ($devices->isEmpty()) {
            $response = [

                'message' => 'No hay datos disponibles',
            ];
        } else {
            $response = [

                'message' => 'Datos recuperados exitosamente',
                'data' => $devices
            ];
        }
        return response()->json($response);
    }

    //Mostrar un unico equipo

    public function show($id)
    {
        $device = Device::find($id);

        if ($device === null) {

            $response = [
                'message' => 'No hay datos disponibles'
            ];
        } else {

            $response = [
                'menssage' => 'Datos recuperados exitosamente',
                'data' => $device
            ];
        }
        return response()->json($response);
    }
    //registrar un device (equipo)

    public function register(RegisterDeviceRequest $request)
    {
        $device = new Device();
        $device->name = $request->name;
        $device->manufacturer = $request->manufacturer;
        $device->model = $request->model;
        $device->serial = $request->serial;
        $device->code = $request->code;
        $device->observation = $request->observation;
        $device->description = $request->description;
        $device->status = $request->status;
        $device->location_id = $request->location_id;
        $device->save();

        $response = [
            'message' => 'Registro creado exitosamente',
            'status' => 201,
            'data' => $device
        ];

        return response()->json($response, 201);
    }

    public function update(RegisterDeviceRequest $request, $id)
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
            $device->status = $request->status;
            $device->location_id = $request->location_id;
            $device->save();

            $response = [
                "message" => "registro actualizado exitosamente",
                "status" => 201,
                "data" => $device
            ];
        }
        return response()->json($response);
    }

    public function destroy(string $id)
    {
        $device = Device::find($id);
        if ($device == null) {
            $data = ["message" => "No existe el equipo solicitado"];
        } else {
            $device->delete();

            $data =[
                "message"=>"Datos de usuario borrado exitosamente",
                "data"=>$device
            ];
        }
        return response()->json($data);
    }
}
