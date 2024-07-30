<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone_number', 'email', 'address', 'product',
        'quantity', 'size', 'total_paid', 'payment_id', 'status',
        'payment_status', 'type'  // Include 'type' in the fillable array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity'); // Ensure pivot data like quantity is accessible
    }
}
