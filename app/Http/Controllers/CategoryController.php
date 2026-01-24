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

    public function getCategories(Request $request)
    {
        try {
            $columns = [
                0 => 'name',
            ];

            $query = Category::query()->select('id', 'name');

            $recordsTotal = Category::count();

            if (!empty($request->search['value'])) {
                $query->where('name', 'like', '%' . $request->search['value'] . '%');
            }

            $recordsFiltered = (clone $query)->count();

            $orderColumnIndex = $request->order[0]['column'] ?? 0;
            $orderDirection   = $request->order[0]['dir'] ?? 'asc';
            $orderColumn      = $columns[$orderColumnIndex] ?? 'name';

            $categories = $query
                ->orderBy($orderColumn, $orderDirection)
                ->offset($request->start)
                ->limit($request->length)
                ->get();

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $categories,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        try{
            $request->validate([
                'name' => 'required|unique:categories,name,' . ($request->category_id ?? ''),
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

    public function destroy($id){
        try{
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json(['status'=>200, 'message'=> 'Category Deleted Successfully']);
        }catch(Exception $e){
            return response()->json(['status'=>500, 'message'=> 'Something Went Wrong: '.$e->getMessage()]);
        }
    }
}
