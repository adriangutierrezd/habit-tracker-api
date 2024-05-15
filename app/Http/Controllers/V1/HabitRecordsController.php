<?php

namespace App\Http\Controllers\V1;

use App\Constants;
use App\Filters\V1\HabitRecordsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreHabitRecordRequest;
use App\Http\Requests\V1\UpdateHabitRecordRequest;
use App\Http\Resources\V1\HabitRecordCollection;
use App\Http\Resources\V1\HabitResource;
use App\Models\Habit;
use App\Models\HabitRecord;
use App\Policies\V1\HabitRecordPolicy;
use App\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HabitRecordsController extends Controller
{
    
    /**
     * Retrieves a listing of resources
     */
    public function index(Request $request)
    {
        $filter = new HabitRecordsFilter();
        $queryItems = $filter->transform($request);
        $habitRecords = HabitRecord::where([
            ...$queryItems,
            'user_id' => $request->user()->id
        ])->get();

        return [
            'data' => new HabitRecordCollection($habitRecords),
            'status' => Constants::HTTP_OK_CODE,
            'message' => Constants::HTTP_FETCHING_MSG
        ];

    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHabitRecordRequest $request)
    {

        $habit = DB::table('habits')->find($request->habitId);
        if(!$habit){
            return [
                'data' => [],
                'status' => Constants::HTTP_NOT_FOUND_CODE,
                'message' => Constants::HTTP_NOT_FOUND_MSG
            ];
        }

        if($habit->user_id !== $request->user()->id){
            return [
                'data' => [],
                'status' => Constants::HTTP_FORBIDDEN_CODE,
                'message' => Constants::HTTP_FORBIDDEN_MSG
            ];
        }

        $habitRecord = HabitRecord::create([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);

        $habit = Habit::where([
            'id' => $habitRecord->habit_id,
        ])->with('habitRecords')->first();


        $end = Carbon::now();
        $start = $end->copy()->subYear();
        $dates = Utils::getDateRange($start, $end);

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

        return [
            'data' => new HabitResource($newHabit),
            'status' => Constants::HTTP_CREATED_CODE,
            'message' => Constants::HTTP_CREATED_MSG
        ];

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHabitRecordRequest $request, HabitRecord $habitRecord)
    {

        $params = [...$request->all()];
        unset($params['habit_id']);
        unset($params['habitId']);


        $habitRecord->update([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);


        $habit = Habit::where([
            'id' => $habitRecord->habit_id,
        ])->with('habitRecords')->first();

        $end = Carbon::now();
        $start = $end->copy()->subYear();
        $dates = Utils::getDateRange($start, $end);

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

        return [
            'data' => new HabitResource($newHabit),
            'status' => Constants::HTTP_OK_CODE,
            'message' => Constants::HTTP_UPDATED_MSG
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, HabitRecord $habitRecord)
    {
        if($request->user()->cannot('delete', [$habitRecord, HabitRecordPolicy::class])){
            return response()->json(['message' => Constants::HTTP_UNAUTHORIZED_MSG], Constants::HTTP_FORBIDDEN_CODE);
        }

        $habitRecord->delete();
    }


}
