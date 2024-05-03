<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailOTPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|string|max:'.config('validation.max_length_uuid'),
            'verify_code' => 'required|string|size:'.config('validation.verify_code.length'),
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'UserId đang để trống',
            'user_id.string' => 'UserId phải là chuỗi',
            'user_id.max' => 'UserId không vượt quá '.config('validation.max_length_uuid').' ký tự',
            'verify_code.required' => 'Code xác thực đang để trống',
            'verify_code.size' => 'Code xác thực phải '.config('validation.length_of_verify_code').' ký tự',
        ];
    }
}
