<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request){

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role'=>$request->role,
            'password'=>Hash::make($request->password)
        ])->assignRole('Usuario');

        $token=$user->createToken('appnettoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
    }

    public function login(LoginUserRequest $request) {
        if(!Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $data = ["message"=>"Usuario No Autorizado"];
            return response()->json($data,401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('appnettokken')->plainTextToken;
        $data = [
            "message"=>"Usuario Autorizado",
            "token" => $token
        ];
        return response()->json($data);
    }

    public function logout() {
        Auth()->user()->tokens()->delete();
        $data = [
            "message"=>"Session cerrada exitosamente"
        ];
        return response()->json($data);
    }


    public function assignRoleToUser(Request $request){
        $user = User::find($request->user_id);
        $role = Role::where('name', $request->role)->first();
        if (!$user){
            $data = [
                'message'=>'El usuario no existe'
            ];
            return response()->json($data);
        }

        if (!$role){
            $data = [
                'message'=>'El rol no esta definido'
            ];
            return response()->json($data);
        }
        $user->assignRole($role->name);
        $data = [
            "message"=>"Rol asignado a usuario correctamente",
            "data"=>$user
        ];

        return response()->json($data);
    }

    public function removeRoleToUser(Request $request){

        $user = User::find($request->user_id);

        if (!$user){
            $data = [
                'message'=>'El usuario no existe'
            ];
            return response()->json($data);
        }

        $role = Role::where('name', $request->role)->first();
        if (!$role){
            $data = [
                'message'=>'El rol no esta definido'
            ];
            return response()->json($data);
        }

        if (!$user->hasRole($role->name)){
            $data = [
                'message'=>'El usuario no tiene rol: '.$role->name,
            ];
            return response()->json($data);
        } else{
            $user->removeRole($role->name);
            $data = [
                "message"=>"Rol ".$role->name." ha sido revocado del usuario ".$user->email,
                "data"=>$user
            ];
            return response()->json($data);
        }
    }
}
