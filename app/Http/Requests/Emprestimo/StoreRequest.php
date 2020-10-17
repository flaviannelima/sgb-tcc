<?php

namespace App\Http\Requests\Emprestimo;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'exemplar' => 'required|exists:exemplares,codigo_barras',
            'leitor' => 'required|exists:leitores,id',
            'password' => 'required'
        ];
    }
}
