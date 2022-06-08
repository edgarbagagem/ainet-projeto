<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPost extends FormRequest
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
            'user_id' => 'required|min:0',
            'nome' => 'required',
            'descricao' => 'max:100',
            'saldo_abertura' => 'required|min:0',
        ];
    }
}
