<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'mobile'=>'required|regex:/(^(09\d{9})?$)/u|unique:users,mobile',
            'password'=>'required|min:3|max:65',
            'confirm-password'=>'required|same:password',
        ];
    }
}
