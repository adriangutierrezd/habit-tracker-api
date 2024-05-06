<?php

namespace App\Filters\V1;
use App\Filters\ApiFilter;

class HabitsFilter extends ApiFilter{

    protected $allowedParams = [
        'frequency' => ['eq']
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];

}
