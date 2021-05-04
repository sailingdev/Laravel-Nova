<?php

namespace App\Http\Requests\FbReporting\FbPagePost;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'fb_page_post_scheduler_id' => ['nullable', 'exists:fb_page_post_schedulers,id'],
            'fb_page_post_id' => ['required', 'exists:fb_page_posts,id'],
            'text' => ['required'],
            'url' => ['nullable', 'string'],
            'start_date' => ['nullable'],
            'page_groups' => ['nullable', 'array'],
            'reference' => ['required', 'string'],
            'media' => ['nullable']
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
