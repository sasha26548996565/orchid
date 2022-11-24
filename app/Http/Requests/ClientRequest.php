<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client.name' => 'required',
            'client.email' => 'required|email',
            'client.password' => 'required|sometimes',
            'client.phone' => 'required|phone:RU|sometimes',
            'client.assessment' => 'required'
        ];
    }
}
