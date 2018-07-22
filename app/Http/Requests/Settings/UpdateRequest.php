<?php

namespace App\Http\Requests\Settings;

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
            'enrollments_start_at' => 'required|date',
            'enrollments_end_at' => 'required|date|after:enrollments_start_at',
            'exchanges_start_at' => 'required|date|after_or_equal:enrollments_start_at',
            'exchanges_end_at' => 'required|date|after:exchanges_start_at',
            //'groups_creation_start_at' => 'required|date|after_or_equal:exchanges_end_at',
            //'groups_creation_end_at' => 'required|date|after:groups_creation_start_at',
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
            'enrollments_start_at' => 'begin date of the enrollments period',
            'enrollments_end_at' => 'end date of the enrollments period',
            'exchanges_start_at' => 'begin date of the exchanges period',
            'exchanges_end_at' => 'end date of the exchanges period',
            'groups_creation_start_at' => 'begin date of the groups creation period',
            'groups_creation_end_at' => 'end date of the groups creation period',
        ];
    }
}
