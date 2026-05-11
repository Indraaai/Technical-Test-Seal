<?php

namespace App\Http\Requests\LeaveRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RejectLeaveRequestRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:255'],
        ];
    }
    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Rejection reason is required.',
            'rejection_reason.string' => 'Rejection reason must be a string.',
            'rejection_reason.max' => 'Rejection reason cannot exceed 255 characters.',
        ];
    }
}
