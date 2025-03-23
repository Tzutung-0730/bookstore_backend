<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // 引入 JWTSubject 介面

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'account',
        'password',
        'email',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 取得 JWT 的識別符號（通常是主鍵）
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回自訂的 JWT 聲明（如果不需要可以返回空陣列）
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
