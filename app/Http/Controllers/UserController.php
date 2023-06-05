<?php
namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
    *   ( Muestra todos los usuarios registrados )
    *   @OA\Schema(
    *       schema="User1Schema",
    *       @OA\Property(property="id", type="number", example="1"),
    *       @OA\Property(property="name", type="string", example="Peter Parker"),
    *       @OA\Property(property="email", type="string", example="pparker@marvel.net"),
    *       @OA\Property(property="phone", type="string", example="0412-999.88.77"),
    *       @OA\Property(
    *           type="array",
    *           property="roles",
    *           @OA\Items(
    *               type="object",
    *               @OA\Property(property="id", type="number", example="1"),
    *               @OA\Property(property="name", type="string", example="Admin"),
    *           )
    *       )
    *   )
    *
    *   @OA\Schema(
    *       schema="User2Schema",
    *       @OA\Property(property="id", type="number", example="2"),
    *       @OA\Property(property="name", type="string", example="Tony Stark"),
    *       @OA\Property(property="email", type="string", example="tstark@marvel.net"),
    *       @OA\Property(property="phone", type="string", example="0412-888.88.88"),
    *       @OA\Property(
    *           type="array",
    *           property="roles",
    *           @OA\Items(
    *               type="object",
    *               @OA\Property(property="id", type="number", example="2"),
    *               @OA\Property(property="name", type="string", example="Supervisor"),
    *           )
    *       )
    *   )
    *
    *   @OA\Get (
    *       path="/api/user",
    *       tags={"Users"},
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
    *                           @OA\Schema(ref="#/components/schemas/User1Schema"),
    *                           @OA\Schema(ref="#/components/schemas/User2Schema"),
    *                       }
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function index()
    {
        // Se recuperan todos datos de la base de datos
        // por medio de Elocuent & Saptie Permission
        $users=User::with('roles')->get();
        //
        if ($users->isEmpty()){     //Se valida si no hay datos
            // Se prepara la respuesta en caso de que no existan datos
            $response = [
                "message"=>"No hay datos disponibles",
                "status"=>200
            ];
        } else {    //Se valida el caso que se recuperen datos
            foreach ($users as $user) {     // Se recorre la colección de usuarios
                foreach ($user->roles as $role) {  // Se recuperan los roles del usuario actual
                    $roles[]=[
                        "id"=>$role->id,
                        "name"=>$role->name
                    ];
                }
                // Se guardan los datos del usuario actual y sus roles
                $data[]=[
                    "id"=>$user->id,
                    "name"=>$user->name,
                    "email"=>$user->email,
                    "phone"=>$user->phone,
                    "roles"=>$roles
                ];
                $roles =[];  //Se inicializa el arreglo para almacenar los roles del próximo usuario
            }
            // Se prepara la respuesta con los datos recuperados, un mensaje de exito y codigo http
            $response = [
                "message"=>"Datos recuperados exitosamente",
                "status"=>200,
                "data"=>$data
            ];
        }
        //
        // Se envia la respuesta a la peticion
        return response()->json($response, 200);
    }

    /**
    *   ( Muestra un usuario seleccionado por id )
    *   @OA\Get (
    *       path="/api/user/{id}",
    *       tags={"Users"},
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
    *                       @OA\Property(property="id", type="number", example="1"),
    *                       @OA\Property(property="name", type="string", example="Peter Parker"),
    *                       @OA\Property(property="email", type="string", example="pparker@marvel.net"),
    *                       @OA\Property(property="phone", type="string", example="0412-999.88.77"),
    *                       @OA\Property(
    *                           type="array",
    *                           property="roles",
    *                           @OA\Items(
    *                               type="object",
    *                               @OA\Property(property="id", type="number", example="1"),
    *                               @OA\Property(property="name", type="string", example="Admin"),
    *                           )
    *                       )
    *                   )
    *               )
    *           )
    *       )
    *   )
    */
    public function show(string $id)
    {
        // Se recuperan todos datos de la base de datos
        // por medio de Elocuent
        $user = User::find($id);
        if ($user===null){
            // Se prepara la respuesta en caso de que no existan datos
            $response = [
                "message"=>"No existe el usuario solicitado",
                "status"=>200
            ];
        } else {
            // Se recuperan los roles del usuario solicitado por medio de
            // Elocuent & Spatie Permission
            foreach ($user->roles as $role) {
                $roles[]=[
                    "id"=>$role->id,
                    "name"=>$role->name
                ];
            }

            // Se guardan los datos del usuario actual y sus roles
            $data =[
                "id"=>$user->id,
                "name"=>$user->name,
                "email"=>$user->email,
                "phone"=>$user->phone,
                "roles"=>$roles
            ];
            // Se prepara la respuesta con los datos recuperados, un mensaje de exito y codigo http
            $response= [
                "message"=>"Datos de usuario recuperado exitosamente",
                "status"=>200,
                "data"=>$data
            ];
        }
        // Se envia la respuesta en formato json
        return response()->json($response);
    }

   /**
     * ( Actualiza los datos de un usuario identificado por id )
     * @OA\Put(
     *     path="/api/user/{id}",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property( property="name", type="string"),
     *                @OA\Property( property="email",type="string"),
     *                @OA\Property( property="phone",type="string"),
     *                @OA\Property( property="role_id",type="integer"),
     *                example={"name": "Peter Parker", "email": "pparker@marvel.net", "phone":"0419-999.88.77" , "role_id":1}
     *            )
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos actualizados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="información de usuario actualizada exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id",type="number",example="1"),
     *                 @OA\Property(property="name", type="string", example="Peter Parker"),
     *                 @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                 @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                 @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                 @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
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
     *
     * )
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        // Se busca el usuario a actualizar por medio de Elocuent
        $user = User::find($id);
        // Se valida el caso que el usuario no exista y se prepara
        // la respuesta para este caso y se envia
        if (!$user){
            $response = [
                "message"=>"Usuario no existe",
                "status" => 400
            ];
            return response()->json($response, 400);
        }
        // En caso que el usuario exista, se verifica el rol
        // recibido en el request, en caso de no existir,
        // se prepara una respuesta y se envía indicando que no
        // existe
        $role = Role::find($request->role_id);
        if (!$role){
            $response = [
                "message"=>"Rol a actualizar no existe",
                "status" => 400
            ];
            return response()->json($response, 400);
        }
        // Se procede a actualizar las propiedades del usuario
        // con los datos recibidos en el request
        $user->name = $request->name;
        // Solo se actualiza el email cuando es necesario,
        // es decir cuando el email recibido en el request
        // difiere del email actual recuperado de la Base de
        // datos
        if ($user->email!=$request->email) {
            $user->email=$request->email;
        }
        $user->phone = $request->phone;
        // Se guardan los datos en la base de datos por medio de
        // Elocuent
        $user->save();
        // Se obtiene el nombre del rol del usuario
        $actualRole = $user->getRoleNames('name')->first();
        // Solo se actualiza el rol cuando es necesario,
        // es decir cuando el nuevo rol recibido en el request
        // difiere del rol actual recuperado de la Base de
        // datos
        if ($actualRole !== $role->name){
            // primero se remueve el rol actual al usuario
            // por medio de Spatie Permission
            $user->removeRole($actualRole);
            // luego se asigna el nuevo rol al usuario
            // por medio de Spatie Permission
            $user->assignRole($role->name);
        }
        // Se prepara la respuesta con los datos recuperados, un mensaje de exito y codigo http
        $response = [
            "message"=>"información de usuario actualizada exitosamente",
            "status"=>200,
            "data"=>$user
        ];
        // Se envia la respuesta en formato json
        return response()->json($response, 200);
    }

    /**
     * ( Elimina los datos de un usuario identificado por id )
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Registro eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registro eliminado exitosamente"),
     *             @OA\Property(
     *                 property="users",
     *                 type="object",
     *                 @OA\Property(property="id",type="number",example="1"),
     *                 @OA\Property(property="name", type="string", example="Peter Parker"),
     *                 @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                 @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                 @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                 @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *             )
     *         )
     *     )
     *
     * )
     */
    public function destroy(string $id)
    {
        // Se busca el usuario a eliminar por medio de Elocuent
        $user = User::find($id);
        // Si el usuario no existe se prepara respuesta con mensaje
        // indicando que el mismo no existe y codigo http
        if ($user===null){
            $response = [
                "message"=>"No existe el usuario",
                "status"=>200
            ];
        } else {
            // Si el usuario existe guarda sus datos para ser enviados
            // en la respuesta
            $data =[
                "id"=>$user->id,
                "name"=>$user->name,
                "email"=>$user->email,
                "phone"=>$user->phone,
            ];
            // Si el usuario existe se procede a borrar por medio de Elocuent
            $user->delete();
            // Se prepara una respuesta con mensaje de exito codigo http y datos
            // del usuario eliminado
            $response = [
                "message"=>"Usuario eliminado exitosamente",
                "status"=>200,
                "data"=>$data
            ];
        }
        return response()->json($response);

    }

}
