<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
 protected $fillable = ['order_number','customer_name','email','phone','address','city','payment_method','subtotal_paisa','status','customer_note'];
 public function items(){ return $this->hasMany(OrderItem::class); }
}
