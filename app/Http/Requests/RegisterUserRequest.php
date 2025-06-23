<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{10}$/',
                'unique:users,phone',
                'max:15',
            ],
            'country_code' => [
                'required',
                'string',
                'regex:/^\+[0-9]{1,4}$/',
                'max:5',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'referral_code' => [
                'nullable',
                'string',
                'size:8',
                'exists:users,referral_code',
            ],
            'terms_accepted' => [
                'required',
                'accepted',
            ],
            'device_name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered',
            'phone.regex' => 'Please enter a valid 10-digit phone number',
            'phone.max' => 'Phone number must not exceed 15 digits',
            'country_code.required' => 'Country code is required',
            'country_code.regex' => 'Please enter a valid country code (e.g., +91)',
            'name.required' => 'Full name is required',
            'name.regex' => 'Name can only contain letters and spaces',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character',
            'password.confirmed' => 'Passwords do not match',
            'referral_code.exists' => 'Invalid referral code',
            'referral_code.size' => 'Referral code must be 8 characters',
            'terms_accepted.required' => 'You must accept the terms and conditions',
            'terms_accepted.accepted' => 'You must accept the terms and conditions',
            'device_name.required' => 'Device name is required',
        ];
    }
}
