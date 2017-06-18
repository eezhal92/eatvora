<?php

namespace App;

use App\Vendor;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'price', 'description', 'contents', 'vendor_id'];

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
}
