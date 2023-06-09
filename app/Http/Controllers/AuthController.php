<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    *   ( Crea un nuevo usuario )
    *   @OA\Post (
    *       path="/api/auth/register",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property( property="name", type="string"),
    *                   @OA\Property( property="email",type="string"),
    *                   @OA\Property( property="phone",type="string"),
    *                   @OA\Property( property="role_id",type="integer"),
    *                   @OA\Property( property="password", type="string"),
    *                   example={"name": "Peter Parker", "email": "pparker@marvel.net", "phone":"0419-999.88.77", "role_id":1, "password":"Test@1234" }
    *               )
    *           )
    *       ),
    *
    *       @OA\Response(
    *           response=201,
    *           description="Usuario Creado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Registro creado"),
    *               @OA\Property(property="status", type="integer", example=201),
    *               @OA\Property(
    *                   property="user",
    *                   type="object",
    *                   @OA\Property(property="name", type="string", example="Peter Parker"),
    *                   @OA\Property(property="email",type="string",example="pparker@marvel.net"),
    *                   @OA\Property(property="phone",type="string",example="0419-999.88.77"),
    *                   @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
    *                   @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
    *                   @OA\Property(property="id",type="number",example="1"),
    *                   @OA\Property(
    *                       type="array",
    *                       property="roles",
    *                       @OA\Items(
    *                           type="object",
    *                           @OA\Property(property="id",type="number",example="1"),
    *                           @OA\Property(property="name",type="string",example="Admin"),
    *                           @OA\Property(property="guard_name",type="string",example="web"),
    *                           @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
    *                           @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
    *                           @OA\Property(
    *                               property="pivot",
    *                               type="object",
    *                               @OA\Property(property="model_id",type="number",example="1"),
    *                               @OA\Property(property="role_id",type="number",example="1"),
    *                               @OA\Property(property="model_type",type="string",example="App\Model\User")
    *                           )
    *                       )
    *                   )
    *               ),
    *               @OA\Property(property="token",type="string",example="8|xUiWgXHxkYUflJe1s8xjLPiGON78YsPG4NzkzK25")
    *           )
    *       )
    *   )
    */
    public function register(RegisterUserRequest $request){
        $role = Role::find($request->role_id);
        if (!$role){
            $response=[
                'message'=>'El rol indicado no se encuentra en la base de datos',
                'status'=>200,
            ];
            return response()->json($response,200);
        }


        //  Se verifica que un Supervisor no pueda crear usuarios con roles
        //  de Admin o Supervisor
        if ((Auth::user()->hasRole('Supervisor')) && ($role->name === 'Supervisor' || $role->name ==='Admin')) {
            $response = [
                "success"=>false,
                "message"=>"Errores de Validacion",
                "data"=>[
                    "role_id"=>[
                        "No tiene autorización para asignar este rol a un usuario"
                    ]
                ],
                "status" => 403
            ];
            return response()->json($response, 403);
        }

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>Hash::make($request->password)
        ])->assignRole($role);
        $user_role = [
            "id"=>$role->id,
            "name"=>$role->name
        ];
        $data = [
            "id"=>$user->id,
            "name"=>$user->name,
            "email"=>$user->email,
            "phone"=>$user->phone,
            "role"=>$user_role
        ];
        // $token=$user->createToken('appnettoken')->plainTextToken;
        $response=[
            'message'=>'Registro creado',
            'status'=>201,
            'user'=>$data
            // 'token'=>$token
        ];
        return response()->json($response,201);
    }

    /**
    *   ( Otorga acceso a un usuario autenticado )
    *   @OA\Post (
    *       path="/api/auth/login",
    *       tags={"Auth_users"},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property( property="email", type="string"),
    *                   @OA\Property( property="password", type="string"),
    *                   example={"email": "pparker@marvel.net", "password":"Test@1234" }
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *       response=200,
    *           description="Login Usuario",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Usuario Autorizado"),
    *               @OA\Property(property="token", type="string", example="8|xUiWgXHxkYUflJe1s8xjLPiGON78YsPG4NzkzK25"),
    *               @OA\Property(property="user_name", type="string", example="Peter Parker"),
    *               @OA\Property(property="user_id", type="number", example="3"),
    *               @OA\Property(property="role_name", type="string", example="Supervisor"),
    *               @OA\Property(property="role_id", type="number", example="2"),
    *           )
    *       )
    *   )
    **/
    public function login(LoginUserRequest $request) {
        if(!Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $data = ["message"=>"Usuario No Autorizado"];
            return response()->json($data,401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $role_name = $user->getRoleNames();
        $role= Role::where('name',$role_name)->firstOrFail();
        $token = $user->createToken('appnettokken')->plainTextToken;
        $data = [
            "message"=>"Usuario Autorizado",
            "token" => $token,
            "user_name"=>$user->name,
            "user_id"=>$user->id,
            "role_name"=>$role->name,
            "role_id"=>$role->id,
        ];
        return response()->json($data);
    }

    /**
    *   ( Revoca el acceso a un usuario autenticado)
    *   @OA\Get (
    *       path="/api/auth/logout",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\Response(
    *           response=200,
    *           description="OK, Token revocado",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Session cerrada exitosamente"),
    *               @OA\Property(property="status", type="string", example="200"),
    *           )
    *       )
    *   )
    */
    public function logout() {
        Auth()->user()->tokens()->delete();
        $response = [
            "message"=>"Session cerrada exitosamente",
            "status"=>"200"
        ];
        return response()->json($response, 200);
    }

    /**
    *   ( Permite a un usuario Admin restablecer la contraseña de cualquier usuario)
    *   @OA\Post (
    *       path="/api/auth/resetPassword",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property( property="email", type="string"),
    *                   @OA\Property( property="password", type="string"),
    *                   example={"email": "pparker@marvel.net", "password":"Test@1234" }
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *       response=200,
    *           description="Restablece la contraseña de un susuario",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string"),
    *               @OA\Property(property="user", type="string"),
    *               @OA\Property(property="status", type="number"),
    *               example={
    *                   "message":"Contraseña restablecida correctamente",
    *                   "user":"pparker@marvel.net",
    *                   "status":"200"
    *               }
    *           )
    *       )
    *   )
    **/
    public function resetPassword(ResetPasswordRequest $request) {
        $user = User::where('email', $request->email)->first();
        if($user){
            $user->password =Hash::make($request->new_password);
            $user->save();
            $user->tokens()->delete();
            $response = [
                "message"=>"Contraseña restablecida correctamente",
                "user"=>$request->email,
                "status"=>200
            ];
        }else{
            $response = [
                "message"=>"El usuario no existe",
                "status"=>200
            ];
        }
        return response()->json($response);
    }

    /**
    *   ( Permite a cualquier usuario que haya iniciado session cambiar su propia contraseña)
    *   @OA\Post (
    *       path="/api/auth/changePassword",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property( property="password", type="string"),
    *                   example={"password":"Test@1234" }
    *               )
    *           )
    *       ),
    *       @OA\Response(
    *       response=200,
    *           description="Restablece la contraseña de un susuario",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string"),
    *               @OA\Property(property="status", type="number"),
    *               example={
    *                   "message":"Cambio  de contraseña exitoso",
    *                   "status":"200"
    *               }
    *           )
    *       )
    *   )
    **/
    public function changePassword(ChangePasswordRequest $request) {
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();
        $response=[
            "message"=>"Cambio de contraseña exitoso",
            "status"=>200
        ];
        Auth()->user()->tokens()->delete();
        return response()->json($response);
    }

    /**
    *   ( Elimina roles previos y asigna un nuevo Rol a un usuario identificado por id )
    *   @OA\Post(
    *       path="/api/auth/roletouser",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property( property="role", type="string"),
    *                   @OA\Property( property="user_id", type="number"),
    *                   example={"role":"Admin", "user_id":"1" }
    *               )
    *           )
    *       ),
    *
    *       @OA\Response(
    *           response=201,
    *           description="Asignacion de Rol exitosa",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="Rol asignado a usuario correctamente"),
    *               @OA\Property(property="status", type="number", example="201"),
    *               @OA\Property(
    *                   property="data",
    *                   type="object",
    *                   @OA\Property(property="id",type="number",example="1"),
    *                   @OA\Property(property="name", type="string", example="Peter Parker"),
    *                   @OA\Property(property="email",type="string",example="pparker@marvel.net"),
    *                   @OA\Property(property="phone",type="string",example="0419-999.88.77"),
    *                   @OA\Property(
    *                       property="role",
    *                       type="object",
    *                       @OA\Property(property="id",type="number",example="1"),
    *                       @OA\Property(property="name",type="string",example="Admin"),
    *                   )
    *               )
    *           )
    *       )
    *   )
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
        $user->syncRoles([]);   //Elimina cualquier rol previamente asignado
        $user->assignRole($role->name);
        $data = [
            "id"=>$user->id,
            "name"=>$user->name,
            "email"=>$user->email,
            "phone"=>$user->phone,
            "role"=>[
                "id"=>$role->id,
                "name"=>$role->name
            ]
        ];
        $response = [
            "message"=>"Rol asignado a usuario correctamente",
            "status"=>"201",
            "data"=>$data
        ];

        return response()->json($response);
    }

    /**
    *   ( Revoca un Rol a un usuario identificado por id )
    *   @OA\Post(
    *       path="/api/auth/rmvroletouser",
    *       tags={"Auth_users"},
    *       security={{"bearerAuth":{}}},
    *       @OA\RequestBody(
    *           @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property( property="role", type="string"),
    *                   @OA\Property( property="user_id", type="number"),
    *                   example={"role":"Admin", "user_id":"1" }
    *               )
    *           )
    *       ),
    *
    *       @OA\Response(
    *           response=200,
    *           description="Rol revocado exitosamente",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="El Rol Admin ha sido revocado del usuario pparker@marvel.net"),
    *               @OA\Property(property="status", type="number", example="200"),
    *               @OA\Property(
    *                   property="user",
    *                   type="object",
    *                   @OA\Property(property="id",type="number",example="1"),
    *                   @OA\Property(property="name", type="string", example="Peter Parker"),
    *                   @OA\Property(property="email",type="string",example="pparker@marvel.net"),
    *                   @OA\Property(property="phone",type="string",example="0419-999.88.77"),
    *               ),
    *               @OA\Property(
    *                   property="role_remove",
    *                   type="object",
    *                   @OA\Property(property="id", type="number",example="1"),
    *                   @OA\Property(property="name", type="string", example="Admin"),
    *               )
    *           )
    *       )
    *   )
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
                "id"=>$user->id,
                "name"=>$user->name,
                "email"=>$user->email,
                "phone"=>$user->phone,
            ];
            $response = [
                "message"=>"Rol ".$role->name." ha sido revocado del usuario ".$user->email,
                "status"=>"200",
                "user"=>$data,
                "role_remove"=>[
                    "id"=>$role->id,
                    "name"=>$role->name,
                ]
            ];
            return response()->json($response);
        }
    }
}
