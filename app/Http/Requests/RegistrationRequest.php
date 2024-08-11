<?php

namespace App\Http\Requests;

use App\Rules\IntegrityRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
class RegistrationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', new IntegrityRule('unique', 'users', 'email')],
            'password' => ['required', 'confirmed', 'min:8', 'max:20', Rules\Password::defaults()],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:20', new IntegrityRule('unique', 'users', 'phone')],
            // 'package_id' => ['required', 'exists:packages,id'],
            'logo' => ['nullable', 'image', 'max:1024'],
        ];
    }

     /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function messages(): array
    {
        return [
            'name.required' => 'Institution Name is required',
            'email.required' => 'Institution Email is required',
            'email.email' => 'Institution Email must be a valid email address',
            'email.unique' => 'Institution Email has already been taken',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation does not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must not be greater than 20 characters',
            'address.required' => 'Institution Address is required',
            'phone.required' => 'Phone Number is required',
            'phone.min' => 'Phone Number must be at least 10 characters',
            'phone.max' => 'Phone Number must not be greater than 20 characters',
            'phone.digits' => 'Phone Number must be a valid phone number',
            // 'package_id.required' => 'Package is required',
            // 'package_id.exists' => 'Package does not exist',
            'logo.image' => 'Institution Logo must be an image',
            'logo.max' => 'Institution Logo must not be greater than 1MB',
        ];
    }
}
