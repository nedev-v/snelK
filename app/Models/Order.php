<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $fillable = ["user_id", "total_price", "pickup_time"];

    public function customer(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function orderDetail(){
        return $this->hasMany(OrderDetail::class, 'order_id');
    }


}
