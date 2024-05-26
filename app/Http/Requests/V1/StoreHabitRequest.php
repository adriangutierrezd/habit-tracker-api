<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:50'],
            'description' => ['sometimes', 'max:255'],
            'color' => ['required', 'max:7'],
            'max_repetitions' => ['required', 'integer']
        ];
    }

    protected function prepareForValidation()
    {
        if($this->maxRepetitions){
            $this->merge([
                'max_repetitions' => $this->maxRepetitions
            ]);
        }
    }
}
