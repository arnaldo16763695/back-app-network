<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */

    public function rules(): array
    {
        return [
            "name"      =>  "required|min:3|max:255",
            "last_name" =>  "required|min:2|max:255",
            "email"     =>  "required|email|unique:users",
            "role"      =>"required|string",

            'password' => ['required', Password::min(8)
                            ->letters()
                            ->numbers()
                            ->mixedCase()
                            ->symbols(),
            ],
        ];
    }

   public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'success'=> false,
            'message'=> 'Errores de Validacion',
            'data'      => $validator->errors()
        ]));
    }

    public function messages() {
       return [
            'name.required'=>'Campo nombre requerido',
            'name.max'=>'Campo nombre puede tener maximo 255 caracteres',
            'name.min'=>'Campo nombre puede tener mínimo 3 caracteres',

            'last_name.required'=>'Campo apellido requerido',
            'last_name.max'=>'Campo apellido puede tener maximo de 255 caracteres',
            'last_name.min'=>'Campo apellido puede tener mínimo de 3 caracteres',

            'email.required'=>'Campo email requerido',
            'email.email'=>'Campo email no tiene el formato correcto',
            'email.unique'=>'el email ingresado ha sido registrado previamente',
            'role.required'=>'Campo rol requerido',
            'role.string'=>'Campo rol debe ser una cadena de letras',

            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'Se requieren al menos 8 caracteres.',
        ];
    }
}
