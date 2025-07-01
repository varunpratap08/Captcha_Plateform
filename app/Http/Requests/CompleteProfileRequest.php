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
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'profile_photo' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpg,jpeg,png,webp',
            ],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'upi_id' => ['required', 'max:50'],
            'agent_referral_code' => [
                'nullable',
                'max:20',
                'exists:agents,referral_code',
                function ($attribute, $value, $fail) use ($userId) {
                    $user = \App\Models\User::find($userId);
                    if ($user && $user->agent_id) {
                        $fail('You have already used an agent referral code.');
                    }
                }
            ],
            'profile_photo_url' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->hasFile('profile_photo_url')) {
                        if (!$this->file('profile_photo_url')->isValid()) {
                            $fail('The profile photo file is not valid.');
                        }
                        if (!in_array($this->file('profile_photo_url')->extension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                            $fail('The profile photo must be a file of type: jpg, jpeg, png, webp.');
                        }
                        if ($this->file('profile_photo_url')->getSize() > 2 * 1024 * 1024) {
                            $fail('The profile photo must not be greater than 2MB.');
                        }
                    } else if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('The profile photo URL must be a valid URL.');
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
