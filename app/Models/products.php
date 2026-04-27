<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'product_title',
        'product_description',
        'product_image',
        'product_badge_popular'
    ];
}
