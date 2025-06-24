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
                File::image()
                    ->max(2048) // 2MB
                    ->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(1000)),
            ],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'upi_id' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9]+$/'],
            'referral_code' => [
                'required',
                'string',
                'max:20',
                'exists:users,referral_code',
                Rule::unique('user_referrals', 'referral_code')->where('used_by', '!=', $userId)
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
