<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller {
 public function index(Request $request){
  $q=Product::query()->where('is_active',true);
  if($request->filled('category')) $q->where('category',$request->string('category'));
  return response()->json(['data'=>$q->orderBy('name')->get()->map(fn($p)=>$this->shape($p))]);
 }
 public function show(Product $product){ abort_unless($product->is_active,404); return response()->json(['data'=>$this->shape($product)]); }
 private function shape(Product $p): array { return ['id'=>$p->id,'external_id'=>$p->external_id,'slug'=>$p->slug,'name'=>$p->name,'price'=>round($p->price_paisa/100,2),'image'=>$p->image_url,'category'=>$p->category]; }
}
