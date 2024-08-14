<?php

namespace App\Models;

use Laravel\Passport\Client as BaseClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseClient
{

    protected $table = 'oauth_clients';
    use HasFactory;

    protected $fillable = [
        'id' ,
        'user_id' ,
        'name' ,
        'secret' ,
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
        ];
    }

    /**
     * 确定客户端是否应该跳过授权提示。
     */
    public function skipsAuthorization(): bool
    {
        return $this->firstParty();
    }
}
