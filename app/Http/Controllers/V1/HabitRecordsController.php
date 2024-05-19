<?php

namespace App\Http\Controllers\V1;

use App\Constants;
use App\Filters\V1\HabitRecordsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ModifyHabitRepsRequest;
use App\Http\Resources\V1\HabitRecordCollection;
use App\Http\Resources\V1\HabitResource;
use App\Models\Habit;
use App\Models\HabitRecord;
use App\Policies\V1\HabitRecordPolicy;
use App\Utils;
use Illuminate\Http\Request;

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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, HabitRecord $habitRecord)
    {
        if($request->user()->cannot('delete', [$habitRecord, HabitRecordPolicy::class])){
            return response()->json(['message' => Constants::HTTP_UNAUTHORIZED_MSG], Constants::HTTP_FORBIDDEN_CODE);
        }

        $habitRecord->delete();
    }

    public function addRepetition(ModifyHabitRepsRequest $request, Habit $habit){

        $record = HabitRecord::where([
            'habit_id' => $habit->id,
            'date' => $request->date
        ])->first();

        if(!$record){
            HabitRecord::create([
                'date' => $request->date,
                'habit_id' => $habit->id,
                'user_id' => $request->user()->id,
                'repetitions' => 1
            ]);
        }else{
            if($record->repetitions >= $habit->max_repetitions){
                return response()->json(['message' => Constants::HTTP_BAD_REQUEST_MSG], Constants::HTTP_BAD_REQUEST_CODE);
            }
            $record->update([
                'repetitions' => $record->repetitions + 1
            ]);
        }

        $updatedHabit = Habit::where([
            'id' => $habit->id,
        ])->with('habitRecords')->first();


        $updatedHabit = Utils::addRecordsToHabit($updatedHabit);
        
        return [
            'data' => new HabitResource($updatedHabit),
            'status' => Constants::HTTP_OK_CODE,
            'message' => Constants::HTTP_FETCHING_MSG
        ];

    }

    public function resetRepetitions(ModifyHabitRepsRequest $request, Habit $habit){

        $record = HabitRecord::where([
            'habit_id' => $habit->id,
            'date' => $request->date
        ])->first();

        if($record){
            $record->update([
                'repetitions' => 0
            ]);
        }

        $updatedHabit = Habit::where([
            'id' => $habit->id,
        ])->with('habitRecords')->first();

        $updatedHabit = Utils::addRecordsToHabit($updatedHabit);
        
        return [
            'data' => new HabitResource($updatedHabit),
            'status' => Constants::HTTP_OK_CODE,
            'message' => Constants::HTTP_FETCHING_MSG
        ];

    }

}
