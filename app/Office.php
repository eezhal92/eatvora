<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['company_id', 'name', 'address', 'is_main'];

    protected $casts = [
        'is_main' => 'boolean',
    ];
}
