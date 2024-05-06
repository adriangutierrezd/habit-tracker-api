<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'habit_id',
        'date',
        'repetitions'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function habit(){
        return $this->belongsTo(Habit::class);
    }

}
