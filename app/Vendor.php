<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'capacity'];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
