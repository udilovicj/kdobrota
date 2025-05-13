<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Prikaz početne stranice
     */
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)->take(6)->get();
        return view('home', compact('featuredProducts'));
    }

    /**
     * Prikaz kataloga proizvoda
     */
    public function catalog(Request $request)
    {
        $categories = Category::withCount('products')->get();
        
        $query = Product::with('category');
        
        // Filter by categories
        if ($request->has('categories')) {
            $query->whereIn('category_id', $request->categories);
        }
        
        // Price filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sort products
        $sort = $request->get('sort', 'recommended');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }
        
        $products = $query->paginate(12);
        
        return view('catalog', compact('categories', 'products'));
    }

    /**
     * Prikaz detalja proizvoda
     */
    public function product($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('product', compact('product'));
    }

    /**
     * Prikaz kontakt stranice
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Prikaz korisničkog profila
     */
    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['orderItems.product'])
            ->latest()
            ->get();
            
        return view('profile', compact('user', 'orders'));
    }

    /**
     * Prikaz dashboard stranice
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Redirect admin users to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['orderItems.product'])
            ->latest()
            ->take(5)
            ->get();
            
        return view('dashboard', compact('user', 'recentOrders'));
    }
}
