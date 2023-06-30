<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'=>'required|min:3|max:50',
        ];
    }
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'=>clean_extra_spaces(trim($this->input('name')))
        ]);
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=> false,
            'message'=> 'Errores de Validacion',
            'data'      => $validator->errors()
        ]));
    }
}
