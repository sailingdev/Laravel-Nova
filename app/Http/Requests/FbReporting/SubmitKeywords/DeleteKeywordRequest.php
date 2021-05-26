<?php

namespace App\Http\Requests\FbReporting\SubmitKeywords;

use Illuminate\Foundation\Http\FormRequest;

class DeleteKeywordRequest extends FormRequest
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
            'id' => ['required', 'exists:submitted_keywords'],
        ];
    }
}
