<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        if ($users->isEmpty()){
            $data = [
                "message"=>"No hay datos disponibles"
            ];
        } else {
            $data = [
                "message"=>"Datos recuperados exitosamente",
                "data"=>$users
            ];
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user===null){
            $data = [ 'message'=>'No se encontro Y el usuario solicitado'];
        } else {
            $data = [
                "message"=>"Datos de usuario recuperado exitosamente",
                "data"=>$user
            ];
        }
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        if ($user->email!=$request->email) {
            $user->email=$request->email;
        }
        $user->phone = $request->phone;
        $user->save();
        $data = [
            "message"=>"Usuario actualizado exitosamente",
            "data"=>$user
        ];
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user===null){
            $data = [ 'message'=>'No se encontro  X el usuario solicitado'];
        } else {
            $user->delete();
            $data = [
                "message"=>"Datos de usuario borrado exitosamente",
                "data"=>$user
            ];
        }
        return response()->json($data);

    }

}
