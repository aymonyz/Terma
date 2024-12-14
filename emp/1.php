<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;    
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;


class LocationController extends Controller
{
    public function store(Request $request)
    {
         
        $request->validate([
            'shipping_phone' => ['required', 'string', 'regex:/^\+966\d{9}$/'],
        ]);
    
        $cartItems = Cart::instance('cart')->content();
        $subtotal = Cart::instance('cart')->subtotal();
        $total = Cart::instance('cart')->total();   
        $shippingDetails = $request->only(['shipping_name', 'shipping_address', 'shipping_city', 'shipping_phone']);
        $paymentDetails = $request->only(['card_number', 'card_expiry', 'card_cvc']);
        
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'السلة فارغة. لا يوجد منتجات لإتمام الطلب.'], 400);
        }
    
        DB::beginTransaction();
    
        try {
            $store_id = null;
            foreach ($cartItems as $item) {
                $store_id = $item->options->Created_by;
                break;
            }

            $payment = DB::table('stores')->where('created_by', $store_id)->value('payment');
            $delivery = DB::table('stores')->where('created_by', $store_id)->value('delivery');
    
            $input = Cart::instance('cart')->total();
            $cleaned = str_replace(',', '', $input);
    
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->store_id = $store_id;
            $order->total_price = (float)$cleaned;
            $order->shipping_name = $request->shipping_name;
            $order->shipping_address = $request->shipping_address;
            $order->shipping_city = $request->shipping_city;
			$order->latitude=$request->latitude;
        $order->longitude=$request->longitude;
            $order->shipping_phone = $request->shipping_phone;
            $order->payment = $payment;
            $order->delivery = $delivery;
            $order->status = 0;
            $order->save();
            
            foreach ($cartItems as $item) {
                
                $product = Product::find($item->id);
                if ($item->qty > $product->quantity) {
                    throw new \Exception("الكمية غير متوفرة للمنتج: " . $product->namemeal);
                }
    
                if (is_array($item->options->category_ids)) {
                    $category_ids_json = json_encode($item->options->category_ids);
                    
                } else {
                    throw new \Exception("التصنيفات ليست بصيغة قائمة للمنتج: " . $item->name);
                }
    
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->price * $item->qty,
                    'category_ids' => $category_ids_json,
                ]);
			
                $orderItem->save();
                
    
                $product->quantity -= $item->qty;
                $product->save();
            }
    
            DB::commit();
            Cart::instance('cart')->destroy();
    
            return redirect('/')->with('done', 'لقد تم إنشاء طلبك بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        $savedOrderItems = OrderItem::where('order_id', $order->id)->get();
    
    foreach ($savedOrderItems as $savedItem) {
       
    }
    
    }


}
