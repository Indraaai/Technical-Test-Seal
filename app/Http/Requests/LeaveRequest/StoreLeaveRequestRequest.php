<?php

namespace App\Http\Requests\LeaveRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:255'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png', 'max:2048'],
            //
        ];
    }
    public function messages(): array
    {
        return [
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'reason.required' => 'Reason for leave is required.',
            'reason.string' => 'Reason must be a string.',
            'reason.max' => 'Reason cannot exceed 255 characters.',
            'attachment.file' => 'Attachment must be a file.',
            'attachment.mimes' => 'Attachment must be a file of type: pdf, doc, docx, jpg, png.',
            'attachment.max' => 'Attachment cannot exceed 2MB in size.',
        ];
    }
}
