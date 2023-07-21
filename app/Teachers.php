<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "teachers";

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
        'upi',
        'title',
        'forename',
        'surname',
    ];
}