<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:schools,email,' . $this->school->id,
            'phone' => 'sometimes|required|string|max:15',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'package_id' => 'sometimes|required|exists:packages,id',
        ];
    }
}
