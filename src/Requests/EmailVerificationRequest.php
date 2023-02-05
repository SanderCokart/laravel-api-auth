<?php

namespace SanderCokart\LaravelApiAuth\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $id
 * @property string $token
 */
class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return ! $this->user()->isVerified();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id'       => ['required', 'string', 'uuid'],
            'token'    => ['required', 'string'],
        ];
    }
}
