<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "classes";

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
        'name',
    ];
}