<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    protected $fillable = ['product_id', 'size', 'quantity'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
