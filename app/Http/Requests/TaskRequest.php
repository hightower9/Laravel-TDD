<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
            'title'    => ['required', 'string', 'max:255'],
            'label_id' => ['nullable', 'integer', 'exists:labels,id'],
            'status'   => ['nullable', Rule::in([
                Task::NOT_STARTED,
                Task::STARTED,
                Task::COMPLETED,
            ])],
        ];
    }
}
