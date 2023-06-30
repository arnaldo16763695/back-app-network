<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateDeviceRequest extends FormRequest
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
            'serial'=>'required|min:3|max:70|unique:devices,serial,'.$this->id,
            'code'=>'required|min:3|max:50',
            'observation'=>'required|max:500',
            'description'=>'required|max:255',
            'status_id'=>'required|integer|exists:statuses,id',
            'type_id'=>'required|integer|exists:types,id',
            'location_id'=>'required|integer|exists:locations,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'=>clean_extra_spaces(trim($this->input('name'))),
            'manufacturer'=>clean_extra_spaces(trim($this->input('manufacturer'))),
            'model'=>clean_extra_spaces(trim($this->input('model'))),
            'serial'=>clean_extra_spaces(trim($this->input('serial'))),
            'code'=>clean_extra_spaces(trim($this->input('code'))),
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
