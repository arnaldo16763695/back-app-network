<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable=[
      'name',
      'manufacturer',
      'model',
      'serial',
      'code',
      'observation',
      'description',
      'location_id',
      'statuses_id'
    ];

    public function location()
    {
      return $this->belongsTo(Location::class);
    }

    public function status()
    {
      return $this->belongsTo(Status::class);
    }
   
}
