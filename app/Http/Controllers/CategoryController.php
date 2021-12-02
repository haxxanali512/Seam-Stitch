<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use GuzzleHttp\Psr7\Message;

class CategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCategories()
    {
        $data = DB::table('category')->get();
        return $this->success('date' ,$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllCategories()
    {
        $data = DB::table('category')->get();
        foreach($data as $category) {
            $category->subCategory=[DB::table('sub_category')->where('category_id', '=', $category->id)->get()];
        }
        return $this->success('Combined Data', $data);
       // return response($data);
        }
        public function getAllSub(Request $request){
            $catogires = DB::table('sub_category')->where('category_id','=', $request->category_id)->get();
            return $this->success('Subcategories', $catogires);
        }

    }


