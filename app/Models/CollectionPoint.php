<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    protected $fillable = ['name','contact','address','map_link'];
}

