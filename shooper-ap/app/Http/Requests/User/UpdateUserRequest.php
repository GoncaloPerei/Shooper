<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
        $method = $this->method();

        if ($method == "PUT") {
            if ($this->has('favorites')) {
                return [
                    'product' => ['required'],
                ];
            } else {
                return [
                    'name' => ['required', 'regex:/^[a-zA-Z ]+$/'],
                    'email' => ['required', 'email', 'unique:users', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
                    'password' => ['required'],
                    'role' => ['required'],
                    'product' => ['required'],
                ];
            }
        } else {
            if ($this->has('favorites')) {
                return [
                    'product' => ['sometimes', 'required'],
                ];
            } else {
                return [
                    'name' => ['sometimes', 'required', 'regex:/^[a-zA-Z ]+$/'],
                    'email' => ['sometimes', 'required', 'email', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/'],
                    'password' => ['sometimes', 'required'],
                    'role' => ['sometimes', 'required'],
                    'product' => ['sometimes', 'required'],
                ];
            }
        }
    }

    protected function prepareForValidation()
    {
        $data = [];

        if ($this->role) {
            $data['role_id'] = $this->role;
        }

        if ($this->product) {
            $data['product_id'] = $this->product;
        }

        $this->merge($data);
    }
}
