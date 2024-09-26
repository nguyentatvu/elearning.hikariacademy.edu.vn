<?php
namespace App;

    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class Uid extends Authenticatable
    {
        use Notifiable;

        protected $guard = 'uid';
        protected $table = 'uid';

        protected $fillable = [
            'uid', 'uid_email','password',
        ];

        protected $hidden = [
            'password'
        ];
    }