<?php

namespace App\Http\Requests\V1;

use App\Policies\V1\HabitRecordPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $habitRecord = $this->route('habitRecord');
        return $this->user() != null && $this->user()->can('update', [$habitRecord, HabitRecordPolicy::class]);
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
                'name' => ['required', 'max:50'],
                'habitId' => ['required', 'exists:habits,id'],
                'date' => ['required', 'date'],
                'repetitions' => ['required', 'numeric'],
            ];
        } else {
            return [
                'name' => ['sometimes', 'required', 'max:50'],
                'habitId' => ['sometimes', 'required', 'exists:habits,id'],
                'date' => ['sometimes', 'date'],
                'repetitions' => ['sometimes', 'required', 'numeric'],
            ];
        }
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
