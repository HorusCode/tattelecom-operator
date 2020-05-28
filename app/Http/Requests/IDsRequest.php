<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IDsRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids*' => 'required|digits',
            'current_id' => 'required'
        ];
    }
}
