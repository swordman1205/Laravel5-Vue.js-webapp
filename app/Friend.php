<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'birthday'
    ];

    public static function saveFriend($userId, $sportsman)
    {
        $birthday = $sportsman['birthday']['year'] . '-' . ($sportsman['birthday']['month'] + 1) . '-' . $sportsman['birthday']['day'];
        return self::create([
            'user_id' => $userId,
            'first_name' => $sportsman['firstName'],
            'last_name' => $sportsman['lastName'],
            'email' => $sportsman['email'],
            'birthday' => $birthday
        ]);
    }
}
