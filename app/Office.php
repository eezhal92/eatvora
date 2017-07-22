<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['company_id', 'name', 'address', 'is_main', 'phone', 'email'];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function admin()
    {
        return $this->employees()->with('user')->where('is_admin', true)->first();
    }
}
