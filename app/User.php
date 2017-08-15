<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const NEXT_WEEK_ORDER_PLACED = 'placed';

    const NEXT_WEEK_ORDER_NOT_PLACED_YET = 'not_placed_yet';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function balance()
    {
        return Balance::where('user_id', $this->id)
            ->pluck('amount')
            ->sum();
    }
}
