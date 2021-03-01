<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class IncidentRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
                    'comments' => 'required',
                    'incidentDate' => 'required',
                    'category' => 'required|exists:category,id',
                    'category' => 'required',
                    'location.latitude' => 'required',
                    'location.longitude' => 'required',
                    'people.*.type' => 'required|in:staff,witness',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'comments.required' => 'Latitude Is Required',
                    'category.required' => 'Latitude Is Required',
                    'category.exists' => 'Please enter valid category',
                    'location.latitude.required' => 'Latitude Is Required',
                    'location.longitude.required' => 'Longitude Is Required',
                    'people.*.type.in' => 'Please enter valid type',
        ];
    }
}
