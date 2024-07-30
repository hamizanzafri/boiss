<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product; // Corrected namespace


class Product extends Model
{
    protected $fillable = ['name', 'details', 'price', 'photo','category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stocks()
{
    return $this->hasMany(\App\Models\Stocks::class); // Adjust namespace if necessary
}
}
