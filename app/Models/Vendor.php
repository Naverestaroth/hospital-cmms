<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [

        'vendor_code',

        'vendor_name',

        'contact_person',

        'phone',

        'email',

    ];
}
