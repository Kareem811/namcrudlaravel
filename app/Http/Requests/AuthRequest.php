<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     return [
    //         'name' => 'required|string',
    //         'username' => 'required|string|unique:users',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string|min:6',
    //         'phone' => 'nullable|string',
    //     ];
    // }
    public function rules(): array
    {
        return [
            'name' => ['required', 'regex:/^[a-zA-Z]+$/u'], // Only letters
            'username' => ['required', 'string', 'unique:users'],
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com|yahoo\.com)$/'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[!@#$%^&*(),.?":{}|<>]/'
            ],
            'phone' => ['nullable', 'string'],
        ];
    }
}
