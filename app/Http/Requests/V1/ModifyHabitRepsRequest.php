<?php

namespace App\Http\Requests\V1;

use App\Policies\V1\HabitPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ModifyHabitRepsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $habit = $this->route('habit');
        return $this->user() != null && $this->user()->can('update', [$habit, HabitPolicy::class]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date']
        ];
    }

}
