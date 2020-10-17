<?php

namespace App\Http\Requests\Leitor;

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
            'cpf' => 'required|min:14|max:14|string|formato_cpf|cpf|unique:leitores,cpf',
            'data_nascimento' => 'required|date|before:today',
            'endereco' => 'required|string|max:255',
            'telefone_residencial' => 'nullable|string|regex:/^\([0-9]{2}\) [0-9]{4}-[0-9]{4}$/',
            'celular' => 'required|string|regex:/^\([0-9]{2}\) [0-9]{5}-[0-9]{4}$/',
            'user' => 'required|exists:users,id|unique:leitores,user'
        ];
    }
}
