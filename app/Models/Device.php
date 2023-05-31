<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable=[
      'name',
      'manofacturer',
      'model',
      'serial',
      'code',
      'observation',
      'description',
      'status',
      'location_id'
    ];

    public function location()
    {
      return $this->belongsTo(Location::class);

    }
}
