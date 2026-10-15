<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Order,Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class OrderController extends Controller {
 public function store(Request $request){
  $data=$request->validate([
   'customer_name'=>['required','string','min:2','max:120'], 'email'=>['nullable','email','max:190'],
   'phone'=>['required','string','min:7','max:30'], 'address'=>['required','string','min:5','max:1000'],
   'city'=>['required','string','max:100'], 'payment_method'=>['required',Rule::in(['cod','bank_transfer'])],
   'customer_note'=>['nullable','string','max:1000'], 'items'=>['required','array','min:1','max:30'],
   'items.*.product_id'=>['required','integer','distinct','exists:products,external_id'], 'items.*.quantity'=>['required','integer','min:1','max:20'],
  ]);
  $order=DB::transaction(function() use($data){
   $ids=collect($data['items'])->pluck('product_id');
   $products=Product::whereIn('external_id',$ids)->where('is_active',true)->get()->keyBy('external_id');
   if($products->count()!==$ids->count()) abort(422,'One or more products are unavailable.');
   $lines=[]; $subtotal=0;
   foreach($data['items'] as $item){ $p=$products[$item['product_id']]; $line=$p->price_paisa*$item['quantity']; $subtotal+=$line; $lines[]=['product_id'=>$p->id,'product_name'=>$p->name,'unit_price_paisa'=>$p->price_paisa,'quantity'=>$item['quantity'],'line_total_paisa'=>$line]; }
   $o=Order::create(['order_number'=>'GC-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),'customer_name'=>$data['customer_name'],'email'=>$data['email']??null,'phone'=>$data['phone'],'address'=>$data['address'],'city'=>$data['city'],'payment_method'=>$data['payment_method'],'subtotal_paisa'=>$subtotal,'status'=>'pending','customer_note'=>$data['customer_note']??null]);
   $o->items()->createMany($lines); return $o;
  });
  return response()->json(['message'=>'Order received','order_number'=>$order->order_number,'total'=>round($order->subtotal_paisa/100,2),'status'=>$order->status],201);
 }
}
