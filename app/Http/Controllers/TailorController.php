<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use App\Models\Tailor;
use App\Models\CNIC;
use App\Models\Bank;
use App\Models\Order;
use App\Models\User;
use App\Models\Rating;
use App\Models\SubCategory;
use App\Models\ProductComponents;
use App\Models\Specification;
use App\Models\ProductAttributes;
use App\Models\ShopAddress;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;


class TailorController extends Controller
{
    use ApiResponser;

    public function updateTailorProfile(Request $request)
    {
        $data = $request->validate([
            'username' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'image' => 'mimes:jpg,png,jped|max:5048',
            'address' => 'string',
            'city' => 'string',
            'postal_code' => 'string',
        ]);
        $tailors = Tailor::get();
        $tailor_profile = 0;
        foreach ($tailors as $tailor) {
            if ($tailor->user_id == Auth::user()->id) {
                $tailor_profile = $tailor->id;
            }
        }
        if ($tailor_profile != 0) {
            $tailor = Tailor::find($tailor_profile);
            $tailor->username = $request->username;
            $tailor->first_name = $request->first_name;
            $tailor->last_name = $request->last_name;
            $tailor->address = $request->address;
            $tailor->city = $request->city;
            $tailor->postal_code = $request->postal_code;
            if ($request->image) {
                $newImage = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $newImage);
                $tailor->image_path = $newImage;
            }
            $tailor->save();
            return $this->success('Information Updated', $tailor);
        } else {
            $tailor = new Tailor();
            $tailor->username = $request->username;
            $tailor->user_id = Auth::user()->id;
            $tailor->first_name = $request->first_name;
            $tailor->last_name = $request->last_name;
            $tailor->address = $request->address;
            $tailor->city = $request->city;
            $tailor->postal_code = $request->postal_code;
            $tailor->save();
            if ($request->image) {
                $newImage = rand() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $newImage);
                $tailor->image_path = $newImage;
                $tailor->save();
            }
            return $this->success('Information added', $tailor);
        }
    }
    public function updatecnic(Request $request)
    {

        $data = $request->validate([
            'id_type' => 'string',
            'name' => 'string',
            'cnic' => 'string',
            'front_image' => 'mimes:jpg,png,jped|max:5048',
            'back_image' => 'mimes:jpg,png,jped|max:5048'
        ]);
        $tailors = Tailor::get();
        $tailor_profile = 0;
        $cnic_id = 0;
        $tailor = Tailor::where('user_id', '=', Auth::user()->id)->first();
        $id = $tailor->id;


        $cnic = CNIC::where('tailor_id', '=', $tailor->id)->count();
        if ($cnic != 0) {
            $cnic_id = CNIC::where('tailor_id', '=', $tailor->id)->first();
            $cnic = CNIC::find($cnic_id->id);
            $cnic->id_type = $request->id_type;
            $cnic->tailor_id = $tailor->id;
            $cnic->name = $request->name;
            $cnic->cnic = $request->cnic;

            if ($request->front_image and $request->back_image) {
                $front = time() . "front" . '.' . $request->front_image->extension();
                $back = time() . "back" . '.' . $request->back_image->extension();
                $request->front_image->move(public_path('images'), $front);
                $request->back_image->move(public_path('images'), $back);
                $cnic->front_image = $front;
                $cnic->back_image = $back;
            }
            $cnic->save();
            return $this->success('CNIC Information Updated', $cnic);
        } else {
            $cnic = new CNIC();
            $cnic->id_type = $request->id_type;
            $cnic->tailor_id = $tailor->id;
            $cnic->name = $request->name;
            $cnic->cnic = $request->cnic;

            if ($request->front_image and $request->back_image) {
                $front = time() . "front" . '.' . $request->front_image->extension();
                $back = time() . "back" . '.' . $request->back_image->extension();
                $request->front_image->move(public_path('images'), $front);
                $request->back_image->move(public_path('images'), $back);
                $cnic->front_image = $front;
                $cnic->back_image = $back;
                $cnic->save();
                return $this->success('CNIC Information added', $cnic);
            }
        }
    }
    public function getAllTailors()
    {
        $tailor = Tailor::all();
        foreach($tailor as $key){
            $key->user_information = User::where('id', $key->user_id)->first();

        }
        return $this->success('All Tailors has been Fetched', $tailor);
    }
    public function viewTailor($id)
    {
        // $tailor = Tailor::where('user_id', '=', $id)->first();
        // $tailor_id = $tailor->id;
        $tailor_profile = Tailor::find($id);
        $user = User::where('id',$tailor_profile->user_id)->first();
        $location = ShopAddress::where('tailor_id','=',$tailor_profile->id)->first();
        $bank = Bank::where('tailor_id','=',$tailor_profile->id)->first();
        $cnic = CNIC::where('tailor_id','=',$tailor_profile->id)->first();

        $tailor_profile->shop =$location;
        $tailor_profile->bank =$bank;
        $tailor_profile->cnic =$cnic;

        $tailor_profile->user =$user;
        return $this->success('Your Profile', $tailor_profile);
    }
    public function getTailor($id)
    {
        $tailor = Tailor::find($id);
        return $this->success('Requested Tailor Profile', $tailor);
    }
    public function getTailorbyRating(Request $request)
    {
        $input = $request->validate([
            'rating' => 'string'
        ]);
        if ($request->rating >= 1) {
            $tailors = DB::table('rating')->whereBetween('rating', [$request->rating, 5])->orderBy('rating', 'desc')->get();
            if ($tailors->count() > 0) {

                return $this->success('Tailors by rating', $tailors);
            } else {
                return $this->error("No tailors were found");
            }
        } else {
            return $this->error('Invalid input');
        }
    }
    public function giveTailorRating(Request $request)
    {
        $input = $request->validate([
            'rating_stars' => 'string',
            'tailor_id' => 'string'
        ]);
        $rating = Rating::create([
            'rating' => $input['rating_stars'],
            'tailor_id' => $input['tailor_id']
        ]);
        $rating->customer_id = Auth::user()->id;
        $rating->save();
        $sum = Rating::where('tailor_id', '=', $request->tailor_id)->sum('rating');
        $count = Rating::where('tailor_id', '=', $request->tailor_id)->count('rating');
        $avg_cal = $sum / $count;
        $avg = count($avg_cal);
        $tailor = Tailor::find($request->tailor_id);
        $tailor->tailor_avg_rating = $avg_cal;
        $tailor->save();
        return $this->success('Tailor rating has been added', $rating);
    }
    public function searchTailor(Request $request)
    {
        $tailor = Tailor::where('first_name', 'LIKE', '%' . $request->searchbar . '%')->orWhere('last_name', 'LIKE', '%' . $request->searchbar . '%')->get();
        if ($tailor->count() > 0) {
            return $this->success('Tailors', $tailor);
        } else {
            return $this->error('No result found');
        }
    }
    public function bestSellers()
    {
        $getTailor = Tailor::orderBy('tailor_avg_rating', 'DESC')->limit(8)->get();
        return $this->success("Tailors", $getTailor);
    }
    public function AuthTailorProducts(){
        $user_id = Auth::user()->id;
        $tailor_profile = Tailor::where('user_id', '=', $user_id)->first();
        $names = [];
        $data = DB::table('product')->where('tailor_id', $tailor_profile->id)->get();

        if($data->count() > 0){
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
                $key->components = $components;
            }


            foreach ($reviews as $key) {
                $key->images = json_decode($key->images);
                $names = $key->user_id;
            }
            foreach ($components as $key) {
                $attributes = ProductAttributes::where('component_id', '=', $key->id)->get();
                $key->attribute = $attributes;
            }

            $data->specifications = $specs;
            $data->reviews = $reviews;
            $user = User::where('id', $names)->first();
            $data->reviews = $reviews;
            return $this->success("Your Products", $data);

        }
else{
    return $this->error("No products found");

}
    //     $tailor_products = Product::where('tailor_id', '=', $tailor_profile->id)->get();
    //    // $components_id = json_decode(Cart::where('tailor_id','=',$tailor_products->tailor_id)->select('components')->get());
    //     foreach($tailor_products as $key){
    //         $components_id = json_decode(Cart::where('product_id','=',$key->id)->first());
    //         $subcategory = SubCategory::where('id','=', $key->sub_category_id)->select('sub_categoryName')->get();

    //         $key->subcategory = $subcategory;
    //     }
    }
    public function orderslist(){

        $user_id = Auth::user()->id;
        $tailor_profile = Tailor::where('user_id', '=', $user_id)->first();
        $orders = Order::where('tailor_id','=',  $tailor_profile->id)->orderBy('id', 'DESC')->get();
        foreach($orders as $key){
            $key->user = User::where('id',$key->user_id)->first();
            $key->product = json_decode($key->product);
        }

        return $this->success("Orders Recieved", $orders);
    }
    public function clicktailorproducts($id){
        $products = Product::where('tailor_id','=',$id)->count();
        if($products > 0){
        $products = Product::where('tailor_id','=',$id)->get();
        foreach($products as $key){
            $key->catalogurl = json_decode($key->catalogurl);

        }
        return $this->success('products', $products);
        }
            return $this->error("no products found");

    }
    public function deleteTailor($id){
        $delete = Tailor::find($id)->delete();
        if($delete){
            return $this->success('Tailor deleted');
        }
        return $this->error("something went wrong");

    }
    public function tailorDashboard(){
        $tailor = Tailor::where('user_id','=',Auth::user()->id)->first();
        $products = Product::where('tailor_id','=', $tailor->id)->count();
        $order = Order::where('tailor_id','=', $tailor->id)->count();
        $order_revenue = Order::where('tailor_id','=', $tailor->id)->sum('final_price');
        $order_revenue -= ($order_revenue/100) * 2;
        $response = [
            'tailor_rating' => $tailor->tailor_avg_rating,
            'products' => $products,
            'orders' => $order,
            'revenue' => $order_revenue
        ];
        return $this->success('tailor dashboard', $response);
    }
    public function locationBased(Request $request){

    }
}
