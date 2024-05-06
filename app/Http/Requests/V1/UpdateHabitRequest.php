<?php

namespace App\Http\Requests\V1;

use App\Policies\V1\HabitPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitRequest extends FormRequest
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

        $method = $this->method();
        if ($method === 'PUT') {
            return [
                'name' => ['required', 'max:100'],
                'description' => ['sometimes', 'max:255'],
                'frequency' => ['required', 'in:DAY,WEEK,MONTH,FORTNIGHT'],
                'max_repetitions' => ['required', 'integer'],
                'color' => ['required', 'max:7']
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'max:100'],
                'description' => ['sometimes', 'max:255'],
                'frequency' => ['sometimes', 'required', 'in:DAY,WEEK,MONTH,FORTNIGHT'],
                'max_repetitions' => ['sometimes', 'required', 'integer'],
                'color' => ['sometimes', 'required', 'max:7']
            ];
        }
    }
}
