<?php

namespace App\Http\Requests\FbReporting;

use Illuminate\Foundation\Http\FormRequest;

class LoadIGAccountIdsRequest extends FormRequest
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
            'fb_page_ids' => ['required']
        ];
    }
}
