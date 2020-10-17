<?php

namespace App\Http\Requests\Relatorio;

use Illuminate\Foundation\Http\FormRequest;

class BuscaRequest extends FormRequest
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
            'mesano'=> 'nullable|regex:/^[0-9]{2}[\/][0-9]{4}$/',
        ];
    }
}
