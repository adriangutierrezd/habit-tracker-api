<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'color',
        'description',
        'frequency',
        'max_repetitions'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function habitRecords(){
        return $this->hasMany(HabitRecord::class);
    }

}
