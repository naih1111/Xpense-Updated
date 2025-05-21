<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'currency_preference' => ['nullable', 'string', 'in:PHP,USD,EUR'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'bio' => ['nullable', 'string', 'max:1000'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }
} 