<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "students";

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
        'forename',
        'surname',
    ];
}