<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "lessons";

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'wonde_id',
        'class_id',
        'room_id',
        'start_time',
        'end_time',
        'period_day',
        'day_value',
    ];
}