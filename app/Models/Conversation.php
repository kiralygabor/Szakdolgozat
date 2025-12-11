<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }
    
    // Helper to get the "other" user in the conversation
    public function getOtherUser($authUserId)
    {
        if ($this->user_one_id == $authUserId) {
            return $this->userTwo;
        }
        return $this->userOne;
    }
}
