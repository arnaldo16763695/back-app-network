<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLocationRequest extends FormRequest
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
            'name'=>'required|min:3|max:50',
            'observation'=>'required|max:255',
            'headquarter_id'=>'required|exists:headquarters,id',
        ];
    }

    public function failedValidation(Validator $validator){
       throw new HttpResponseException(response()->json([
        'success'=> false,
        'message'=> 'Errores de Validacion',
        'data'      => $validator->errors()
    ]));
    }
}
