<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request){

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role'=>$request->role,
            'password'=>Hash::make($request->password)
        ]);

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
}
