<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id', 'office_id', 'is_admin'];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function office() {
        return $this->belongsTo(Office::class);
    }
}
