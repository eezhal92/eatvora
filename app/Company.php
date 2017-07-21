<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name'];

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function mainOffice()
    {
        return $this->offices()->where('is_main', true)->first();
    }
}
