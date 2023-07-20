<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schools extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "schools";

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
        'name',
        'wonde_id',
    ];
}