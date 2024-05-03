<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:'.config('validation.first_name.max_length'),
            'last_name' => 'required|string|max:'.config('validation.last_name.max_length'),
            'email' => 'required|string|email|min:'.config('validation.email.min_length').'|max:'.config('validation.email.max_length').'|unique:users,email',
            'password' => 'required|string|min:'.config('validation.password.min_length').'|max:'.config('validation.password.max_length'),
            'day_of_birth' => 'required|integer|between:'.config('validation.day.min_value').','.config('validation.day.max_value'),
            'month_of_birth' => 'required|integer|between:'.config('validation.month.min_value').','.config('validation.month.max_value'),
            'year_of_birth' => 'required|integer|min:'.config('validation.year.min_value'),
            'gender' => 'required|integer|between:'.config('validation.gender.men').','.config('validation.gender.female'),
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Họ không được để trống',
            'first_name.string' => 'Họ phải là chuỗi',
            'first_name.max' => 'Họ không được quá '.config('validation.first_name.max_length').' ký tự',
            'last_name.required' => 'Tên không được để trống',
            'last_name.string' => 'Tên phải là chuỗi',
            'last_name.max' => 'Tên không được quá '.config('validation.last_name.max_length').' ký tự',
            'email.required' => 'Email không được để trống',
            'email.string' => 'Email phải là chuỗi',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không vượt quá '.config('validation.email.max_length').' ký tự',
            'email.min' => 'Email không ít hơn '.config('validation.email.min_length').' ký tự',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.string' => 'Mật khẩu phải là chuỗi',
            'password.min' => 'Mật khẩu không được ít hơn '.config('validation.password.min_length').' ký tự',
            'password.max' => 'Mật khẩu không được quá '.config('validation.password.max_length').' ký tự',
            'day_of_birth.required' => 'Ngày sinh không được để trống',
            'day_of_birth.integer' => 'Ngày sinh phải là số',
            'day_of_birth.between' => 'Ngày sinh phải không hợp lệ',
            'month_of_birth.required' => 'Tháng sinh không được để trống',
            'month_of_birth.integer' => 'Tháng sinh phải là số',
            'month_of_birth.between' => 'Tháng sinh phải không hợp lệ',
            'year_of_birth.required' => 'Năm sinh không được để trống',
            'year_of_birth.integer' => 'Năm sinh phải là số',
            'year_of_birth.min' => 'Năm sinh không được nhỏ hơn '.config('validation.year.min_value'),
            'gender.required' => 'Giới tính đang để trống',
            'gender.integer' => 'Giới tính không hợp lệ',
            'gender.between' => 'Giới tính không hợp lệ',
        ];
    }
}
