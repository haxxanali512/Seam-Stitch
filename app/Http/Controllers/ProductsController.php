<?php

namespace App\Http\Controllers;

use App\Models\ProductAttributes;
use App\Models\ProductComponents;
use App\Models\Specification;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;
use App\Traits\ApiResponser;
use App\Models\User;
use App\Models\Tailor;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;


class ProductsController extends Controller
{
    use ApiResponser;

    public function getProductByCategory(Request $request)
    {
        $input_values = $request->validate([
            'filter' => 'string',
            'category_id' => 'string'
        ]);
        $data = DB::table('sub_category')->where('category_id', '=', $request->category_id)->get();
        $product = new Collection();
        foreach ($data as $subcategory) {
            $getProduct = new Collection();
            if ($request->filter === 'latest') {
                $getProduct = DB::table('product')->where('sub_category_id', '=', $subcategory->id)->orderBy('created_at', 'desc')->get();
                foreach ($getProduct as $data) {
                    $data->catalogurl = json_decode($data->catalogurl);
                }
            } else {
                $getProduct = DB::table('product')->where('sub_category_id', '=', $subcategory->id)->orderBy('sold_product', 'desc')->get();
                foreach ($getProduct as $data) {
                    $data->catalogurl = json_decode($data->catalogurl);
                }
            }

            $product = $product->merge($getProduct);
        }

        //print_r($subcategory->id);
        return $this->success('Product with Category to subcategory', $product);
    }

    public function getProductBySubcategory($id)
    {
        $data = DB::table('product')->where('sub_category_id', '=', $id)->get();
        foreach ($data as $key) {
            $key->catalogurl = json_decode($key->catalogurl);
        }
        return $this->success('Product with subcategory', $data);
    }

    public function filter(Request $request)
    {
        $min = intval($request->min_price);
        $max = intval($request->max_price);
        $avg_rating_product = intval($request->rating);
        $tailor_avg = intval($request->tailor_rating);
        $name = $request->name;
        $getProduct = DB::table('product')
            ->where('name', 'like', '%' . "$name" . '%')
            ->whereBetween('price', [$min, $max])
            ->whereBetween('average_rating', [$avg_rating_product, 5])
            ->rightJoin('tailor', 'product.tailor_id', '=', 'tailor.id')
            ->whereBetween('tailor_avg_rating', [$tailor_avg, 5])
            ->orderBy('average_rating', 'desc')
            ->get();
        foreach ($getProduct as $data) {
            $data->catalogurl = json_decode($data->catalogurl);
        }
        return $this->success('products', $getProduct);

        //        if($request->category != ""){
        //            $data = DB::table('sub_category')->where('category_id', '=', $request->category)->get();
        //            $getProduct;
        //            foreach($data as $subcategory) {
        //                $getProduct=DB::table('product')->where('sub_category_id', '=', $subcategory->id)
        //                    ->where('brand', 'like', '%'."$request->brand".'%')
        //                    ->whereBetween('price', [$request->min_price,$request->max_price])->where('product_rating','like','%'.$request->rating.'%')->get();
        //                if($getProduct->count()>0)
        //                    $product[]=$getProduct;
        //            }
        //            foreach($product as $data) {
        //                $data->catalogurl=json_decode($data->catalogurl);
        //            }
        //            return $this->success('products', $product);
        //        }
        //
        //        else{
        //

    }
    public function getProductsByTailor($id)
    {
        $data = DB::table('product')->where('tailor_id', '=', $id)->get();
        foreach ($data as $key) {
            $key->catalogurl = json_decode($key->catalogurl);
        }
        return $this->success('Product against tailor', $data);
    }
    public function showProduct()
    {
        $data = DB::table('category')
            ->leftjoin('sub_category', 'category.id', "=", 'sub_category.category_id')
            ->leftjoin('product', 'sub_category.id', '=', 'product.sub_category_id')
            ->get();
        return $this->success('date', $data);
    }
    public function showAll()
    {
        $data = DB::table('product')->orderBy('id', 'DESC')->get();
        foreach ($data as $key) {
            $key->catalogurl = json_decode($key->catalogurl);
        }
        return $this->success('date', $data);
    }

    public function showRespective($id)
    {
        $names = [];
        $data = DB::table('product')->where('id', $id)->first();
        $tailor_id = $data->tailor_id;
        $tailor = Tailor::where('id',$tailor_id)->first();
        $data->tailor = Tailor::where('id',$tailor_id)->first();
        $user_shop = User::where('id',$tailor->user_id)->first();
        $data->catalogurl = json_decode($data->catalogurl);
        $components = ProductComponents::where('product_id', '=', $id)->get();
        $specs = Specification::where('product_id', '=', $id)->get();
        $reviews = ProductRating::where('product_id', '=', $id)
            ->rightJoin('users', 'users.id', '=', 'product_rating.user_id')
            ->get();
        foreach ($reviews as $key) {
            $key->images = json_decode($key->images);
            $names = $key->user_id;
        }
        foreach ($components as $key) {
            $attributes = ProductAttributes::where('component_id', '=', $key->id)->get();
            $key->attribute = $attributes;
        }
        $data->component = $components;
        $data->specifications = $specs;
        $data->reviews = $reviews;
        $user = User::where('id', $names)->first();

        $data->reviews = $reviews;
        $data->shop_name = $user_shop->shop_name;
        return $this->success('data', $data);
    }
    public function searchProduct(Request $request)
    {

        $searched = DB::table('product')->where('name', 'LIKE', '%' . "$request->seachbar" . '%')->get();
        foreach ($searched as $data) {
            $data->catalogurl = json_decode($data->catalogurl);
        }
        return $this->success('Searched Product', $searched);
    }
    public function publishpost(Request $request)
    {
        $data = $request->validate([
            'tailor_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'subcategory_id' => 'string',
            'discount' => 'string',
            'images' => 'max:5048',
            'quantity' => 'string',
            'price' => 'string',
            'shipping_type' => 'string',
            'product_id' => 'string'
        ]);
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = rand() . "." . $file->extension();
                $file->move(public_path() . '/images', $name);
                $imgData[] = $name;
            }
            if ($request->product_id) {
                $product_update = Product::find($request->product_id);
                $product_update->name = $request->title;
                $product_update->description = $request->description;
                $product_update->sub_category_id = $request->subcategory_id;
                $product_update->discount = $request->discount;
                $product_update->quantity = $request->quantity;
                $product_update->price = $request->price;
                $product_update->shipping_type = $request->shipping_type;
                $product_update->catalogurl = json_encode($imgData);
                $product_update->save();
                $product_update->catalogurl = json_decode($product_update->catalogurl);
                $input = $request->componenet;
                foreach ($input as $key => $value) {
                    $inser_compo = ProductComponents::where('product_id','=',$product_update->id)->updateOrCreate([
                        'name' =>$key
                    ]);
                    foreach ($value as $attribute) {
                        $newImage = rand() . '.' . $attribute['photo']->extension();
                        $attribute['photo']->move(public_path('images'), $newImage);

                        $insert_attr = ProductAttributes::find($inser_compo->id);
                        $insert_attr->name  =  $attribute['name'];
                        $insert_attr->price = $attribute['price'];
                        $insert_attr->photo = $newImage;
                        $insert_attr->save();
                    }
                }
                foreach ($request->specs as $name => $value) {

                    $specs = Specification::where('product_id','=',$product_update->id)->updateOrCreate([
                        'name'=>$name,
                        'value'=>$value
                    ]);
                }
                return $this->success('Product has been updated', $product_update);
            } else {
                $userid = Auth::user()->id;
                $tailor = Tailor::where('user_id', '=', $userid)->first();
                $tailor_id = $tailor->id;
                $createdProduct = Product::create([
                    'name' => $data['title'],
                    'description' => $data['description'],
                    'discount' => $data['discount'],
                    'catalogurl' => json_encode($imgData),
                    'sub_category_id' => $data['subcategory_id'],
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                    'shipping_type' => $data['shipping_type']
                ]);
                $createdProduct->tailor_id = $tailor_id;
                $createdProduct->save();
                $createdProduct->catalogurl = json_decode($createdProduct->catalogurl);
                $input = $request->componenet;
                foreach ($input as $key => $value) {
                    $inser_compo = ProductComponents::create([
                        'name' => $key,
                        'product_id' => $createdProduct->id,
                    ]);
                    foreach ($value as $attribute) {
                        $newImage = rand() . '.' . $attribute['photo']->extension();
                        $attribute['photo']->move(public_path('images'), $newImage);

                        $insert_attr = ProductAttributes::create([
                            'component_id' =>  $inser_compo->id,
                            'name' => $attribute['name'],
                            'price' => $attribute['price'],
                            'photo' => $newImage,
                        ]);
                    }
                }
                foreach ($request->specs as $name => $value) {
                    $specs = Specification::create([
                        'name' => $name,
                        'value' => $value,
                        'product_id' => $createdProduct->id
                    ]);
                }
                return $this->success('Product has been published', $createdProduct);
            }
        } else {
            return $this->error("Images are required");
        }
    }
    public function showBrands()
    {
        $brands = DB::table('product')->select('brand')->get();
        return $this->success('All brands', $brands);
    }
    public function giveProductRating(Request $request)
    {
        $data = $request->validate([
            'rating' => 'string',
            'review_message' => 'string',
            'product_id' => 'string',
            'images' => 'max:5048',
            'tailor_rating' => 'string',
        ]);
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = rand() . "." . $file->extension();
                $file->move(public_path() . '/images', $name);
                $imgData[] = $name;
            }
            $rating_product = ProductRating::create([
                'product_id' => $data['product_id'],
                'rating' => $data['rating'],
                'review' => $data['review_message'],
                'user_id' => Auth::user()->id,
                'images' => json_encode($imgData)
            ]);
            $rating_product->images = json_decode($rating_product->images);
            $sum = ProductRating::where('product_id', '=', $request->product_id)->sum('rating');
            $count = ProductRating::where('product_id', '=', $request->product_id)->count('rating');
            $avg_cal = $sum / $count;
            $avg = round($avg_cal);
            $product = Product::find($request->product_id);
            $product->average_rating = $avg;
            $product->save();

            //tailor_rating
            $rating = Rating::create([
                'rating' => $data['tailor_rating'],
                'tailor_id' => $product->tailor_id
            ]);
            $rating->customer_id = Auth::user()->id;
            $rating->save();
            $sum = Rating::where('tailor_id', '=', $product->tailor_id)->sum('rating');
            $count = Rating::where('tailor_id', '=', $product->tailor_id)->count('rating');
            $avg_cal = $sum / $count;
            $avg = round($avg_cal);
            $tailor = Tailor::find($product->tailor_id);
            $tailor->tailor_avg_rating = $avg_cal;
            $tailor->save();
            $response = [
                'product rating' => $rating_product,
                'tailor rating' => $rating
            ];
            return $this->success('Rating has been added', $response);
        }
    }
    public function getProductRating(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'string'
        ]);
        $product = DB::table('product')->select('average_rating')->where('id', '=', $request->product_id)->get();
        return $this->success('Average rating of product', $product);
    }
    public function deleteProduct($id)
    {
        $names = [];
        $product = Product::find($id);
        $id =  $product->tailor_id;
        $product->delete();
        $data = Product::where('tailor_id','=',$id)->get();
        if($data){
            foreach($data as $key){
                $subcategory = SubCategory::where('id',$key->sub_category_id)->get();
                $key->catalogurl = json_decode($key->catalogurl);
             $key->subcategory = $subcategory;
             $components = ProductComponents::where('product_id', '=', $key->id)->get();
             $specs = Specification::where('product_id', '=', $key->id)->get();
             $reviews = ProductRating::where('product_id', '=', $key->id)
                 ->rightJoin('users', 'users.id', '=', 'product_rating.user_id')
                 ->select('product_id', 'rating', 'review', 'images', 'user_id', 'name')
                 ->get();
            }
            foreach ($reviews as $key) {
                $key->images = json_decode($key->images);
                $names = $key->user_id;
            }
            foreach ($components as $key) {
                $attributes = ProductAttributes::where('component_id', '=', $key->id)->get();
                $key->attribute = $attributes;
            }
            $data->component = $components;
            $data->specifications = $specs;
            $data->reviews = $reviews;
            $user = User::where('id', $names)->first();
            $data->reviews = $reviews;

        return $this->success('product has been deleted', $data);
    }
}
}
