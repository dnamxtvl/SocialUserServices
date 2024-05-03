<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetNewPasswordAfterForgotRequest extends FormRequest
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
            'otp_id' => 'required|string|max:'.config('validation.max_length_uuid'),
            'token' => 'required|string|max:'.config('validation.token.max_length'),
            'password' => 'required|string|min:'.config('validation.password.min_length').'|max:'.config('validation.password.max_length'),
        ];
    }

    public function messages(): array
    {
        return [
            'otp_id.required' => 'UserId đang để trống',
            'otp_id.string' => 'UserId phải là chuỗi',
            'otp_id.max' => 'UserId không vượt quá '.config('validation.max_length_uuid').' ký tự',
            'token.required' => 'Token đang để trống',
            'token.string' => 'Token phải là chuỗi',
            'token.max' => 'Token không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.string' => 'Mật khẩu phải là chuỗi',
            'password.min' => 'Mật khẩu không được ít hơn '.config('validation.password.min_length').' ký tự',
            'password.max' => 'Mật khẩu không được quá '.config('validation.password.max_length').' ký tự',
            'remember_me.boolean' => 'Remember me phải là boolean',
        ];
    }
}
