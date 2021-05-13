<?php

namespace App\Http\Requests\FbReporting\FbPagePostScheduler;

use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleRequest extends FormRequest
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
            'start_date' => ['required'],
            'page_groups' => ['required', 'array'],
            'fb_page_post_id' => ['required', ]
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'page_groups' => json_decode($this->page_groups)
        ]);
    }
}
