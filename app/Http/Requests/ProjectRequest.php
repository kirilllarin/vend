<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends CustomFormRequest
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
            'title' => 'required',
            'users' => 'required|array|filled',
            'columns' => 'required|array|filled',
            'columns.*.title' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'columns.*.title.required' => 'Columns title field is required'
        ];
    }
}
