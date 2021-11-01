<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateProfileRequest extends FormRequest
{
    use ApiRequestValidationTrait;
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
            'name' => 'required|min:4|max:20',
            'user_name' => 'required|min:4|max:20',
            'email' => 'required|email|unique:users,id,'.$this->user()->id,
            'password' => 'required|min:6|confirmed',
            'avatar' => 'dimensions:min_width=256,min_height=256|image',
        ];
    }
}
