<?php

namespace App\Http\Requests;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|min:6|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'day_of_birth' => 'required|integer|between:1,31',
            'month_of_birth' => 'required|integer|between:1,12',
            'year_of_birth' => 'required|integer|min:1905',
            'gender' => 'required|integer|between:0,1'
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Họ không được để trống',
            'first_name.string' => 'Họ phải là chuỗi',
            'first_name.max' => 'Họ không được quá 255 ký tự',
            'last_name.required' => 'Tên không được để trống',
            'last_name.string' => 'Tên phải là chuỗi',
            'last_name.max' => 'Tên không được quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.string' => 'Email phải là chuỗi',
            'email.email' => 'Email không đúng định dạng',
            'email.min' => 'Email không được ít hơn 6 ký tự',
            'email.max' => 'Email không được quá 255 ký tự',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.string' => 'Mật khẩu phải là chuỗi',
            'password.min' => 'Mật khẩu không được ít hơn 8 ký tự',
            'password.max' => 'Mật khẩu không được quá 255 ký tự',
            'day_of_birth.required' => 'Ngày sinh không được để trống',
            'day_of_birth.integer' => 'Ngày sinh phải là số',
            'day_of_birth.between' => 'Ngày sinh phải nằm trong khoảng 1 đến 31',
            'month_of_birth.required' => 'Tháng sinh không được để trống',
            'month_of_birth.integer' => 'Tháng sinh phải là số',
            'month_of_birth.between' => 'Tháng sinh phải nằm trong khoảng 1 đến 12',
            'year_of_birth.required' => 'Năm sinh không được để trống',
            'year_of_birth.integer' => 'Năm sinh phải là số',
            'year_of_birth.min' => 'Năm sinh không được nhỏ hơn 1905',
            'gender.required' => 'Giới tính đang để trống',
            'gender.integer' => 'Giới tính không hợp lệ',
            'gender.between' => 'Giới tính không hợp lệ'
        ];
    }
}
