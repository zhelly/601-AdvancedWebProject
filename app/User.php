<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username','vote', 'email'];
	
	/**
     * Get the questions posted by a user
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'user_id');
    }
}
