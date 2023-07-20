<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiAuthTokens extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = "api_auth_tokens";

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
        'service_name',
        'token',
        'expires_at',
    ];
}