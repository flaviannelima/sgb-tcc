<?php

namespace App\Http\Requests\Obra;

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
            'tipo_material'=> 'required|exists:tipos_material,id',
            'categoria'=> 'required|exists:categorias,id',
            'titulo'=> 'required|string|max:255',
            'editora'=> 'required|exists:editoras,id',
            'localizacao'=> 'required|string|min:14|max:14|regex:/^[0-9]{2}.[0-9]{2}.[0-9]{2} [A-Z]{2} [A-Z]{2}$/',
            'volume'=>'integer|nullable|min:1',
            'observacao'=>'string',
            'autores' => 'required|exists:autores,id|array',
            'autores.*' => 'distinct',
            'assuntos' => 'required|array|exists:assuntos,id',
            'assuntos.*' => 'distinct',
            'observacao' => 'string|nullable'
        ];
    }
}
