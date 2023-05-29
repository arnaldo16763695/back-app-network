<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'observation',
        'headquarter_id'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function headquarter()

    {
        return $this->belongsTo(Headquarter::class);
    }
}

