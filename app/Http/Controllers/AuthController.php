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

/**
* @OA\Info(
*             title="API Inventario de Redes",
*             version="1.0",
*             description="Control de creación, actualización, visualización y eliminación de registros de usuarios"
* )
* @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      type="http",
     *      scheme="bearer"
     * )
*
* @OA\Server(url="http://localhost:8000")
*/
class AuthController extends Controller
{
/**
     * ( Crea un nuevo usuario )
     * @OA\Post (
     *     path="/api/auth/register",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property( property="name", type="string"),
     *                @OA\Property( property="email",type="string"),
     *                @OA\Property( property="phone",type="string"),
     *                @OA\Property( property="password", type="string"),
     *                example={"name": "Peter Parker", "email": "pparker@marvel.net", "phone":"0419-999.88.77", "password":"Test@1234" }
     *            )
     *        )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Usuario Creado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rol revocado usuario correctamente"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Peter Parker"),
     *                 @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                 @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                 @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                 @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *                 @OA\Property(property="id",type="number",example="1"),
     *                 @OA\Property(
     *                     type="array",
     *                     property="roles",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id",type="number",example="1"),
     *                         @OA\Property(property="name",type="string",example="Admin"),
     *                         @OA\Property(property="guard_name",type="string",example="web"),
     *                         @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                         @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *                         @OA\Property(
     *                             property="pivot",
     *                             type="object",
     *                             @OA\Property(property="model_id",type="number",example="1"),
     *                             @OA\Property(property="role_id",type="number",example="1"),
     *                             @OA\Property(property="model_type",type="string",example="App\Model\User")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="token",type="string",example="8|xUiWgXHxkYUflJe1s8xjLPiGON78YsPG4NzkzK25")
     *         )
     *     )
     * )
     */
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
            'message'=>'Registro creado',
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
    }

/**
     * ( Otorga acceso a un usuario autenticado )
     * @OA\Post (
     *     path="/api/auth/login",
     *     tags={"Users"},
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property( property="email", type="string"),
     *                @OA\Property( property="password", type="string"),
     *                example={"email": "pparker@marvel.net", "password":"Test@1234" }
     *            )
     *        )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Login Usuario",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Usuario Autenticado"),
     *                 @OA\Property(property="token", type="string", example="8|xUiWgXHxkYUflJe1s8xjLPiGON78YsPG4NzkzK25"),
     *             )
     *         )
     *     )
     * )
**/

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

/**
     * ( Revoca el acceso a un usuario autenticado )
     * @OA\Get (
     *     path="/api/auth/logout",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK, Token revocado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Session cerrada exitosamente"),
     *         )
     *     )
     * )
     */
    
     public function logout() {
        Auth()->user()->tokens()->delete();
        $data = [
            "message"=>"Session cerrada exitosamente"
        ];
        return response()->json($data);
    }



/**
     * ( Asigna un Rol a un usuario identificado por id )
     * @OA\Post(
     *     path="/api/auth/roletouser",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property( property="role", type="string"),
     *                @OA\Property( property="user_id", type="number"),
     *                example={"role":"Admin", "user_id":"1" }
     *            )
     *        )
     *     ),
     * 
     *     @OA\Response(
     *         response=201,
     *         description="Asignacion de Rol a Usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rol asignado a usuario correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id",type="number",example="1"),
     *                 @OA\Property(property="name", type="string", example="Peter Parker"),
     *                 @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                 @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                 @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                 @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *                
     *                 @OA\Property(
     *                     type="array",
     *                     property="roles",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id",type="number",example="1"),
     *                         @OA\Property(property="name",type="string",example="Admin"),
     *                         @OA\Property(property="guard_name",type="string",example="web"),
     *                         @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                         @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *                         @OA\Property(
     *                             property="pivot",
     *                             type="object",
     *                             @OA\Property(property="model_id",type="number",example="1"),
     *                             @OA\Property(property="role_id",type="number",example="1"),
     *                             @OA\Property(property="model_type",type="string",example="App\Model\User")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

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

/**
     * ( Retira un Rol a un usuario identificado por id )
     * @OA\Post (
     *     path="/api/auth/rmvroletouser",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property( property="role", type="string"),
     *                @OA\Property( property="user_id", type="number"),
     *                example={"role":"Admin", "user_id":"1" }
     *            )
     *        )
     *     ),
     * 
     *     @OA\Response(
     *         response=201,
     *         description="Revoca un Rol a un Usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rol revocado usuario correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id",type="number",example="1"),
     *                 @OA\Property(property="name", type="string", example="Peter Parker"),
     *                 @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                 @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                 @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                 @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *                
     *                 @OA\Property(
     *                     type="array",
     *                     property="roles",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id",type="number",example="1"),
     *                         @OA\Property(property="name",type="string",example="Admin"),
     *                         @OA\Property(property="guard_name",type="string",example="web"),
     *                         @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                         @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *                         @OA\Property(
     *                             property="pivot",
     *                             type="object",
     *                             @OA\Property(property="model_id",type="number",example="1"),
     *                             @OA\Property(property="role_id",type="number",example="1"),
     *                             @OA\Property(property="model_type",type="string",example="App\Model\User")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

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
