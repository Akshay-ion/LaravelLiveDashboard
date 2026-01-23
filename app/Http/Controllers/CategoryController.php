<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return view('category');
    }

    public function getCategories(Request $request){
        dd($request->all());
        try{
            // $columns =
        }catch(Exception $e){
            return response()->json(['status'=>500, 'message'=> 'Something Went Wrong: '.$e->getMessage()]);
        }
    }

    public function store(Request $request){
        try{
            $request->validate([
                'name'=>'required|unique:categories,name',
                'category_id'=>'nullable|exists:categories,id'
            ]);

            if($request->category_id){
                $category = Category::find($request->category_id);
                $category->name = $request->name;
                $category->update();

                return response()->json(['status'=>200, 'message'=> 'Category Updated Successfully']);
            }

            Category::create([
                'name'=>$request->name,
            ]);

            return response()->json(['status'=>200, 'message'=> 'Category Added Successfully']);
        }catch(Exception $e){
            return response()->json(['status'=>500, 'message'=> 'Something Went Wrong: '.$e->getMessage()]);
        }

    }
}
