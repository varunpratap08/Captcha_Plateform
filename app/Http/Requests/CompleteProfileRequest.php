<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CompleteProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Will be handled by auth middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'profile_photo' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpg,jpeg,png,webp',
            ],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'upi_id' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9]+$/'],
            'agent_referral_code' => [
                'nullable',
                'string',
                'max:20',
                'exists:agents,referral_code',
                function ($attribute, $value, $fail) use ($userId) {
                    $user = \App\Models\User::find($userId);
                    if ($user && $user->agent_id) {
                        $fail('You have already used an agent referral code.');
                    }
                }
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
            'profile_photo.image' => 'The file must be an image.',
            'profile_photo.max' => 'The image must not be larger than 2MB.',
            'profile_photo.dimensions' => 'The image dimensions should be maximum 1000x1000px.',
            'email.unique' => 'This email is already registered.',
            'date_of_birth.before' => 'The date of birth must be a date before today.',
        ];
    }
}
