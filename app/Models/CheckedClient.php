<?php

namespace App\Models;

use Laravel\Passport\Client as BaseClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckedClient extends BaseClient
{

    protected $table = 'check_oauth_clients';
    use HasFactory;

    protected $fillable = [
        'id' ,
        'user_id' ,
        'name' ,
        'redirect' ,
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
        ];
    }
}
