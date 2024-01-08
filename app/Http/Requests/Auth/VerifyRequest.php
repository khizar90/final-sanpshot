<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyRequest extends FormRequest
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
            'email' => 'required|unique:users,email|email',
            'country_code' => 'required',
            'phone' => 'required',
        ];
    }
    // public function messages(): array
    // {
    //     return [
    //         'country_code.required' => 'The country code is required.',
    //         'phone.required' => 'The phone is required.',
    //         'email.required' => 'The email is required.',
    //         'eamil.unique' => 'Email is already taken'
    //     ];
    // }

    public function failedValidation(Validator $validator)
    {
        $errorMessage = implode(', ', $validator->errors()->all());

        throw new HttpResponseException(response()->json([
            'status'   => false,
            'action' => $errorMessage
        ]));
    }
}
