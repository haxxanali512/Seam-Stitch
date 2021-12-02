<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Location;
use App\Models\Measurements;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Collection;
use App\Models\ProductAttributes;
use App\Models\ProductComponents;
use App\Models\Tailor;
use App\Traits\ApiResponser;

class OrderController extends Controller
{
    use ApiResponser;
    public function orders(Request $request)
    {
        return $this->success("test", $request->component);
    }
    public function completeOrder(Request $request)
    {

        foreach ($request->cart as $key) {
            $cart_data = Cart::where('id', $key)->first();
            $products = Product::where('id', $cart_data->product_id)->first();

            $products->quantity = ($products->quantity) - ($cart_data->quantity);
            $products->sold_product += $cart_data->quantity;
            $products->save();
            $product = Product::find($cart_data->product_id);
            $components = json_decode($cart_data->components);
            //   foreach ($components as $key) {
            //     if (ProductAttributes::where('id', $key)->count() > 0) {
            //         $names = ProductComponents::where('id',$key)->get();
            //         $product = $product->merge(ProductAttributes::where('id', $key)->get());
            //     }
            // }

            $order = new Order();
            // foreach($cart_data as $s){

            $attr = new Collection();
            if (empty($cart_data->components) OR str_contains($cart_data->components,'null')==true) {

                $order = Order::create([
                    'user_id' => $cart_data->user_id,
                    'tailor_id' => $product->tailor_id,
                    'product' => json_encode($product),
                    'status' => 'processing',
                    'quantity' => $cart_data->quantity,
                    'shipping_type' => $cart_data->shipping_type,
                    'final_price' => $cart_data->final_price,
                ]);

                $order->product = json_decode($order->product);
                echo($cart_data->id);
                die();
                 $delete_Cart = Cart::where('id', $cart_data->id)->delete();

                //   return $this->success("Order details ",$order);
            } else {

                foreach (json_decode($cart_data->components) as $subkey) {
                    $temp = ProductAttributes::where('id', $subkey)->get();
                    foreach ($temp as $key) {
                        $key->components = ProductComponents::where('id', $key->component_id)->first();
                    }
                    $attr = $attr->merge($temp);
                }
                $product->Attributes =  $attr;

                // }
                $order = Order::create([
                    'user_id' => $cart_data->user_id,
                    'tailor_id' => $product->tailor_id,
                    'product' => json_encode($product),
                    'status' => 'processing',
                    'quantity' => $cart_data->quantity,
                    'shipping_type' => $cart_data->shipping_type,
                    'final_price' => $cart_data->final_price,
                ]);

                $order->product = json_decode($order->product);

                $delete_Cart = Cart::where('id', $cart_data->id)->delete();

            }
        }
        $user_id = Auth::user()->id;
        $orders = Order::where('user_id', '=', $user_id)->get();

        foreach ($orders as $key) {
            $key->user = User::where('id', $key->user_id)->first();
            $key->user->location = Location::where('user_id','=', $key->user_id)->first();
            $key->product = json_decode($key->product);
        }

        return $this->success("Order details ", $orders);
    }
    public function statusUpdate(Request $request)
    {
        $order = Order::find($request->id);
        $order->status = $request->status;
        $order->tracking_number = $request->tracking_number;
        $order->save();
        return $this->success('status has been updated', $order->status);
    }
    public function authOrders()
    {
        $user_id = Auth::user()->id;
        $orders = Order::where('user_id', '=', $user_id)->orderBy('id','DESC')->get();
        foreach ($orders as $key) {
            $key->user = User::where('id', $key->user_id)->first();
            $key->measurements = Measurements::where('user_id', '=', $key->user_id)->first();
            $key->product = json_decode($key->product);
        }
        return $this->success("Orders Recieved", $orders);
    }
    public function totalRevenue()
    {
        $tailor = Tailor::where('user_id', '=', Auth::user()->id)->first();
        $orders = Order::where('tailor_id', '=', $tailor->id)->sum('final_price');
        return $this->success('', $orders);
    }
}
