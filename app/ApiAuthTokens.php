<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiAuthTokens extends Model
{
    protected $table = "api_auth_tokens";

    public $timestamps = true;

    protected $fillable = [
        'service_name',
        'token',
        'expires_at',
    ];
}