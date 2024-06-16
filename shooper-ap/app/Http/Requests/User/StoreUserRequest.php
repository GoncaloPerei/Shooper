<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'regex:/^[a-zA-Z ]+$/'],
            'email' => ['required', 'email', 'unique:users', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
            'password' => ['required'],
            'phoneNumber' => ['regex:/^[0-9]/'],
            'role' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'role_id' => $this->role,
            'phone_number' => $this->phoneNumber
        ]);
    }
}
