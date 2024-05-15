<?php

namespace App\Http\Controllers\V1;

use App\Constants;
use App\Filters\V1\HabitsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreHabitRequest;
use App\Http\Requests\V1\UpdateHabitRequest;
use App\Http\Resources\V1\HabitCollection;
use App\Http\Resources\V1\HabitResource;
use App\Models\Habit;
use App\Models\HabitRecord;
use App\Policies\V1\HabitPolicy;
use App\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HabitsController extends Controller
{

    
    /**
     * Retrieves a listing of resources
     */
    public function index(Request $request)
    {
        $filter = new HabitsFilter();
        $queryItems = $filter->transform($request);
        $habits = Habit::where([
            ...$queryItems,
            'user_id' => $request->user()->id
        ])->with(['habitRecords' => function($query) {
            $query->where('date', '>=', now()->subYear());
        }])->get();

        $end = Carbon::now();
        $start = $end->copy()->subYear();
        $dates = Utils::getDateRange($start, $end);

        $completedHabits = $habits->map(function (Habit $habit) use($dates) {
            $newHabit = $habit->replicate();
            $oldRecords = $newHabit->habitRecords;
            $oldRecordsFormat = [];
            foreach($oldRecords as $oldRecord){
                $oldRecordsFormat[$oldRecord['date']] = $oldRecord;
            }
            $newHabit->unsetRelation('habitRecords');
            $newHabit->id = $habit->id;
            $newRecords = [];
            foreach($dates as $date){
                if(array_key_exists($date, $oldRecordsFormat)){
                    $newRecords[] = $oldRecordsFormat[$date];
                    continue;
                }
                $record = new HabitRecord([
                    'id' => 0,
                    'user_id' => 0,
                    'habit_id' => $habit->id,
                    'date' => $date,
                    'repetitions' => 0
                ]);
                $newRecords[] = $record;
            }

            $newHabit->setRelation('habitRecords', $newRecords);
            return $newHabit;
        });

        return [
            'data' => new HabitCollection($completedHabits),
            'status' => Constants::HTTP_OK_CODE,
            'message' => Constants::HTTP_FETCHING_MSG
        ];

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHabitRequest $request)
    {
        $habit = Habit::create([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);

        return [
            'data' => new HabitResource($habit),
            'status' => Constants::HTTP_CREATED_CODE,
            'message' => Constants::HTTP_CREATED_MSG
        ];

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHabitRequest $request, Habit $habit)
    {
        $habit->update([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);

        return [
            'data' => new HabitResource($habit),
            'status' => Constants::HTTP_OK_CODE,
            'message' => Constants::HTTP_UPDATED_MSG
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Habit $habit)
    {
        if($request->user()->cannot('delete', [$habit, HabitPolicy::class])){
            return response()->json(['message' => Constants::HTTP_UNAUTHORIZED_MSG], Constants::HTTP_FORBIDDEN_CODE);
        }

        $habit->delete();
    }

}
