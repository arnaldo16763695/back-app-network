<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
class RegisterHeadquarterRequest extends FormRequest
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
    
    public function rules(): array{
        return [
            'name'=>'required|min:3|max:255',
            'state'=>'required|min:4|max:255',
            'city'=>'required|min:3|max:255',
            'address'=>'required|min:5|max:500'

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
