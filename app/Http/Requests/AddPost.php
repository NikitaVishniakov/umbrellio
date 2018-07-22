<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPost extends FormRequest
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
            'header' => 'required|string|max:255',
            'content' => 'required|string',
            'login' => 'string|max:255',
            'user_ip' => 'ip',
        ];
    }
}
