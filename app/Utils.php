<?php

namespace App;

use App\Models\Habit;
use App\Models\HabitRecord;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;

class Utils{

    public static function getDateRange(Carbon $start, Carbon $end): array {
        $period = new DatePeriod(
            $start,
            new DateInterval('P1D'),
            $end
        );
    
        $dateRange = [];
        foreach ($period as $date) {
            $dateRange[] = $date->format('Y-m-d');
        }
    
        $dateRange[] = $end->format('Y-m-d');
        return $dateRange;
    }

    public static function addRecordsToHabit(Habit $habit): Habit{

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
        return $newHabit;
    }
    
}
