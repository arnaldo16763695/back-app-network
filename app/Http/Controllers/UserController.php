<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * ( Muestra todos los usuarios registrados )
     * @OA\Get (
     *     path="/api/user",
     *     tags={"Users"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Datos recuperados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Datos recuperados exitosamente"),
     *             @OA\Property(
     *                 type="array",
     *                 property="users",
     *                 @OA\Items(
     *                     type="object",
     *                      @OA\Property(property="id",type="number",example="1"),
     *                      @OA\Property(property="name", type="string", example="Peter Parker"),
     *                      @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                      @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                      @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                      @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *
     *
     *                 )
     *
     *             )
     *         )
     *     )
     *
     * )
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
     * ( Muestra un usuario identificado por id )
     * @OA\Get (
     *     path="/api/user/{id}",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos recuperados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Datos recuperados exitosamente"),
     *             @OA\Property(
     *                 type="array",
     *                 property="users",
     *                 @OA\Items(
     *                     type="object",
     *                      @OA\Property(property="id",type="number",example="1"),
     *                      @OA\Property(property="name", type="string", example="Peter Parker"),
     *                      @OA\Property(property="email",type="string",example="pparker@marvel.net"),
     *                      @OA\Property(property="phone",type="string",example="0419-999.88.77"),
     *                      @OA\Property(property="created_at",type="string",example="2023-05-15 02:36:54"),
     *                      @OA\Property(property="updated_at",type="string",example="2023-05-15 02:36:54"),
     *
     *
     *                 )
     *
     *             )
     *         )
     *     )
     *
     * )
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

        $user = User::find($id);
        if (!$user){
            $response = [
                "message"=>"Usuario no existe",
                "status" => 400
            ];
            return response()->json($response, 400);
        }

        $role = Role::find($request->role_id);
        if (!$role){
            $response = [
                "message"=>"Rol a actualizar no existe",
                "status" => 400
            ];
            return response()->json($response, 400);
        }
        $user->name = $request->name;
        if ($user->email!=$request->email) {
            $user->email=$request->email;
        }
        $user->phone = $request->phone;
        $user->save();

        $actualRole = $user->getRoleNames('name')->first();
        if ($actualRole !== $role->name){
            $user->removeRole($actualRole);
            $user->assignRole($role->name);
        }

        $data = [
            "message"=>"información de usuario actualizada exitosamente",
            "data"=>$user
        ];

        return response()->json($data);
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
