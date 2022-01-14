<?php

namespace App\Http\Requests\Number;

use Illuminate\Foundation\Http\FormRequest;

class PostNumberCommentRequest extends FormRequest
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
            'author' => 'required',
            'content' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ];
    }

    public function messages()
    {
        return [
            'author.required' => 'Įveskite vardą.',
            'content.required' => 'Įveskite komentarą.',
            'g-recaptcha-response.required' => 'Patvirtinkite, kad esate ne robotas!',
            'g-recaptcha-response.recaptcha' => 'Patvirtinkite, kad esate ne robotas!',
        ];
    }
}
