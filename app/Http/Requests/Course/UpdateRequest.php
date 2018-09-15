<?php

namespace App\Http\Requests\Course;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'group_min' => 'required|numeric|min:0',
            'group_max' => 'required|numeric|greater_than_field:group_min',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'group_min' => 'minimum allowed group size',
            'group_max' => 'maximum allowed group size',
        ];
    }
}
