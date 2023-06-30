<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
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
            'name'=>'required|min:1|max:50|unique:statuses,name',
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
