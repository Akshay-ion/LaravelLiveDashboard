<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $productCount = Product::count();
        return view('dashboard', compact('categoryCount', 'productCount'));
    }
}
