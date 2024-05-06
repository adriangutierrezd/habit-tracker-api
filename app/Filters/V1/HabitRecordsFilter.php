<?php

namespace App\Filters\V1;
use App\Filters\ApiFilter;

class HabitRecordsFilter extends ApiFilter{

    protected $allowedParams = [
        'habit_id' => ['eq'],
        'date' => ['eq', 'lt', 'lte', 'gt', 'gte']
    ];

    protected $columnMap = [
        'habit_id' => 'habitId'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];

}

