<?php

namespace App\Http\Requests;
use App\Rules\IntegrityRule;
use Illuminate\Support\Facades\Log;

class UpdateSampleModelRequest extends BaseRequest
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
        $id_to_ignore = $this->route('sample_model')->id;
        Log::info($id_to_ignore);
        return [
            'name' => ['sometimes', 'required', new IntegrityRule('unique', 'sample_models', 'name', $id_to_ignore )]
        ];
    }
}
