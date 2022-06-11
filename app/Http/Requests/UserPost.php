<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        //dd($this->user->id);

        if (is_object($this->user)) {
            $id = $this->user->id;
        } else {
            $id = null;
        }


        return [
            'name' => 'required',
            'tipo' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'foto' => 'nullable|image|max:8192',   // Máximum size = 8Mb
        ];
    }
}
