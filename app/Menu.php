<?php

namespace App;

use App\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function vendorName()
    {
        return $this->vendor->name;
    }

    public function formattedPrice()
    {
        return 'Rp. ' . number_format($this->price);
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path;

        if (!$path) {
            return '';
        }

        return \Storage::disk('public')->url($path);
    }
}
