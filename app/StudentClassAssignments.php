<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClassAssignments extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "student_class_assignments";

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'student_id',
        'class_id',
    ];
}