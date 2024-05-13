<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HabitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'description' => $this->description,
            'userId' => $this->user_id,
            'frequency' => $this->frequency,
            'maxRepetitions' => $this->max_repetitions,
            'records' => new HabitRecordCollection($this->whenLoaded('habitRecords'))
        ];
    }
}
