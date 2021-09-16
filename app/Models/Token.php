<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = 'token';

    public function getTokenAttribute($value)
    {
        return $value->toArray();
    }

    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = $value->toJson();
    }
}
