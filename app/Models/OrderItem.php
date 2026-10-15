<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {
 protected $fillable = ['product_id','product_name','unit_price_paisa','quantity','line_total_paisa'];
 public function order(){ return $this->belongsTo(Order::class); }
}
