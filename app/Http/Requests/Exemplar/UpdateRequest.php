<?php

namespace App\Http\Requests\Exemplar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'codigo_barras' => 'required|integer|min:0|unique:exemplares,codigo_barras,'.$this->exemplar->id,
            'edicao' => 'nullable|integer|min:0',
            'ano' => 'nullable|integer|min:0',
            'obra' => 'required|exists:obras,id'
        ];
    }
}
