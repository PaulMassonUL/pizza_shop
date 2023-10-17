<?php

namespace pizzashop\auth\api\entities;

use pizzashop\auth\api\dto\CredentialsDTO;
use pizzashop\auth\api\dto\UserDTO;

class User extends \Illuminate\Database\Eloquent\Model
{

    protected $connection = 'auth';
    protected $table = 'users';
    protected $primaryKey = 'email';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['email', 'password', 'active', 'activation_token', 'activation_token_expiration_date', 'refresh_token', 'refresh_token_expiration_date', 'reset_passwd_token', 'reset_passwd_token_expiration_date', 'username'];

    public function toDTO(){
        $user = new UserDTO(new CredentialsDTO($this->email, $this->password));
        $user->active = $this->active;
        $user->activation_token = $this->activation_token;
        $user->activation_token_expiration_date = $this->activation_token_expiration_date;
        $user->refresh_token = $this->refresh_token;
        $user->refresh_token_expiration_date = $this->refresh_token_expiration_date;
        $user->reset_passwd_token = $this->reset_passwd_token;
        $user->reset_passwd_token_expiration_date = $this->reset_passwd_token_expiration_date;
        $user->username = $this->username;
        return $user;
    }
}