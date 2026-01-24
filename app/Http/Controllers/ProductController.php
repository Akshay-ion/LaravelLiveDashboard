<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $categories = Category::get(['id', 'name']);
        return view('product', compact('categories'));
    }

    public function getCategories(Request $request)
    {
        try {

            $query = Product::query()
                        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                        ->select(
                            'products.id',
                            'products.name',
                            'products.category_id',
                            'categories.name as category_name'
                        );

            $recordsTotal = Product::count();

            if (!empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                    ->orWhere('categories.name', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = (clone $query)->count();

            $orderColumnIndex = $request->order[0]['column'] ?? 0;
            $orderDirection   = $request->order[0]['dir'] ?? 'asc';

            switch ($orderColumnIndex) {
                case 0:
                    $orderColumn = 'products.id';
                    break;

                case 1:
                    $orderColumn = 'products.name';
                    break;

                case 2:
                    $orderColumn = 'categories.name';
                    break;

                default:
                    $orderColumn = 'products.name';
            }

            $data = $query
                    ->orderBy($orderColumn, $orderDirection)
                    ->offset($request->start ?? 0)
                    ->limit($request->length ?? 10)
                    ->get();

            return response()->json([
                        'draw'            => intval($request->draw),
                        'recordsTotal'    => $recordsTotal,
                        'recordsFiltered' => $recordsFiltered,
                        'data'            => $data,
                    ]);

        } catch (Exception $e) {
            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try{
            // dd($request->all());
            $request->validate([
                'id' => 'nullable|exists:products,id',
                'name' => 'required|string|max:255|unique:products,name,' . ($request->product_id ?? ''),
                'category_id' => 'required|exists:categories,id',
            ]);

            if($request->product_id){
                $product = Product::find($request->product_id);
                $product->update([
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Product updated successfully.'
                    ]);

            }

            Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Product created successfully.'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Product deleted successfully.'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
