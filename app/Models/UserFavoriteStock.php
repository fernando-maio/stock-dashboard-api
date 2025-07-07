<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavoriteStock extends Model
{
    protected $fillable = [
        'user_id',
        'symbol'
    ];
}
