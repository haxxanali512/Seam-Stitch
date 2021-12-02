<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    use ApiResponser;
    public function showsubCategories()
    {
        $data = DB::table('sub_category')->get();
        return $this->success('date', $data);
    }
    public function addSubCategory(Request $req)
    {
        $data = $req->validate([
            'name' => 'string',
            'category_id' => 'string'
        ]);
        $insert_data = SubCategory::create([
            'sub_categoryName' => $data['name'],
        ]);
        $insert_data->category_id = $data['category_id'];
        $insert_data->save();
        return $this->success('Subcategories has been added', $insert_data);
    }
}
