<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'name'=>'required|min:3|max:255',
            'email'=>'required|email|unique:users',
            'phone'=>'min:4',
            'role'=>'required',
            'password'=>['required', Password::min(8)
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

}
