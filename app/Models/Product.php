<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
 protected $fillable = ['external_id','slug','name','price_paisa','image_url','category','is_active'];
 protected $casts = ['is_active'=>'boolean','price_paisa'=>'integer'];
}
