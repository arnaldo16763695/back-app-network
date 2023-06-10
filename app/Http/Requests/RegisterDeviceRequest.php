<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegisterDeviceRequest extends FormRequest
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
            'manufacturer'=>'required|min:5|max:50',
            'model'=>'required|min:3|max:50',
            'serial'=>'required|min:3|max:70',
            'code'=>'required|min:3|max:50',
            'observation'=>'required|min:3|max:500',
            'description'=>'required|min:3|max:255',
            'status'=>'required|min:1|max:1',
            'location_id'=>'required',
        ];
    }

    public function filedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=> false,
            'message'=> 'Errores de Validacion',
            'data'      => $validator->errors()
        ]));

    }
}
