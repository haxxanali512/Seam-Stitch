<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductAttributes;
use App\Models\ProductComponents;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    use ApiResponser;
    public function addcart(Request $request)
    {
        if ($request->cart_id) {
            $cartValue = Cart::find($request->cart_id);
            $cartValue->components = json_encode($request->component);
            $cartValue->quantity = $request->quantity;
            $cartValue->shipping_type = $request->shipping;
            $cartValue->final_price = $request->final_price;
            $cartValue->save();
            $cart_data = Cart::where('user_id', $cartValue->user_id)->get();
            foreach ($cart_data as $key) {
                $key->components = json_decode($key->components);
                $product = new Collection();
                if ($key->components) {
                    foreach ($key->components as $subkey) {
                        if (ProductAttributes::where('id', $subkey)->count() > 0) {
                            //  $names = ProductComponents::where('id',$subkey->id)->select('name')->get();
                            $product = $product->merge(ProductAttributes::where('id', $subkey)->get());
                        }
                    }
                    $key->components = $product;
                    // $key->component_name = $names;
                }
            }

            foreach ($cart_data as $key) {
                $product_id = Product::where('id', 24)->get();
                foreach($product_id as $subkey)
                $subkey->catalogurl = json_decode($subkey->catalogurl);
                $key->product = $product_id;
            }
            return $this->success("data has been updated", $cart_data );
        } else {
            $insert = Cart::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
                'components' => json_encode($request->component),
                'quantity' => $request->quantity,
                'shipping_type'=>$request->shipping,
                'final_price'=> $request->final_price
            ]);
            $cart_data = Cart::where('user_id', $insert->user_id)->get();
            foreach ($cart_data as $key) {
                $key->components = json_decode($key->components);
                $product = new Collection();
                if ($key->components) {
                    foreach ($key->components as $subkey) {
                        if (ProductAttributes::where('id', $subkey)->count() > 0) {
                            //  $names = ProductComponents::where('id',$subkey->id)->select('name')->get();
                            $product = $product->merge(ProductAttributes::where('id', $subkey)->get());
                        }
                    }
                    $key->components = $product;
                    // $key->component_name = $names;
                }
            }

            foreach ($cart_data as $key) {
                $product_id = Product::where('id', $key->product_id)->get();
                foreach($product_id as $subkey)
                $subkey->catalogurl = json_decode($subkey->catalogurl);
                $key->product = $product_id;
            }




            return $this->success("data has been added", $cart_data);
        }
    }
    public function getCart()
    {
        $cartData = Cart::where('user_id', '=', Auth::user()->id)->orderBy('id','DESC')->get();
        foreach ($cartData as $key) {
            $key->components = json_decode($key->components);
            $product = new Collection();
            if ($key->components) {
                foreach ($key->components as $subkey) {
                    if (ProductAttributes::where('id', $subkey)->count() > 0) {
                        $names = ProductComponents::where('id',$subkey)->get();
                        $product = $product->merge(ProductAttributes::where('id', $subkey)->get());
                    }
                }
                $key->components = $product;
                $key->component_name = $names;
            }
        }

        foreach ($cartData as $key) {
            $product_id = Product::where('id','=',$key->product_id)->get();
            foreach($product_id as $subkey)
            $subkey->catalogurl = json_decode($subkey->catalogurl);
            $key->product = $product_id;
        }


        return $this->success("Your cart data", $cartData);
    }
    public function delete($id)
    {
        $delete = Cart::find($id)->delete();
        return $this->success("Data has been deleted");
    }
}
