<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProblem extends FormRequest
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
            'title' => 'required|string|min:3|max:50',
            'description' => 'required|string|min:20|max:1000',
            'input_format' => 'required|string|min:20|max:1000',
            'output_format' => 'required|string|min:20|max:1000',
            'sample_input' => 'required|string|min:1|max:100',
            'sample_output' => 'required|string|min:1|max:100',
        ];
    }
}
