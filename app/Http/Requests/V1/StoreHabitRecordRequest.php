<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreHabitRecordRequest extends FormRequest
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
            'habitId' => ['required', 'exists:habits,id'],
            'date' => ['sometimes', 'date'],
            'repetitions' => ['required', 'numeric'],
        ];
    }

    protected function prepareForValidation()
    {
        if($this->habitId){
            $this->merge([
                'habit_id' => $this->habitId
            ]);
        }
    }
}
