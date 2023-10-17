<?php

namespace pizzashop\auth\api\entities;

class User extends \Illuminate\Database\Eloquent\Model
{

    protected $connection = 'auth';
    protected $table = 'users';
    protected $primaryKey = 'email';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['email', 'password', 'active', 'activation_token', 'activation_token_expiration_date', 'refresh_token', 'refresh_token_expiration_date', 'reset_passwd_token', 'reset_passwd_token_expiration_date', 'username'];

}