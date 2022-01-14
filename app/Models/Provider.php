<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public $table = 'providers';

    //public $timestamps = true;

    public $fillable = [
        'title'
    ];
}
