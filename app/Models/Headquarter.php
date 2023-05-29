<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headquarter extends Model
{
    use HasFactory;
  protected $fillable=[
        'name',
        'state',
        'city', 
        'address'
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

}
