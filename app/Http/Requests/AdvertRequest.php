<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdvertRequest extends Request
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
            'job-title' => 'required',
            'salary' => 'required|integer',
            'description' => 'required',
            'business-name' => 'required',            
            'location' => 'required',
            'street' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'country' => 'required',
        ];
    }
}
