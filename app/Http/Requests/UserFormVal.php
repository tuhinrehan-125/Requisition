<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormVal extends FormRequest
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
        if($this->User_Role==2)
        {
            $rules['department_name'] = 'required';
        }
        $rules['User_Name'] = 'required';
        $rules['User_Role'] = 'required';
        $rules['email'] = 'required|string|email|max:255|unique:users';
        $rules['username'] = 'required|string|unique:users';
        $rules['password'] = 'required|string|min:6|confirmed';

        return $rules;
    }
}
