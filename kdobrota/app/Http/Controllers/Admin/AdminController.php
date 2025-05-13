<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Recent orders for the table
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(5)
            ->get();

        // Category distribution data
        $categoryData = Category::withCount('products')
            ->get()
            ->map(function ($category) {
                return [$category->name, $category->products_count];
            });

        // Orders timeline data (last 7 days)
        $orderTimelineData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($order) {
                return [
                    $order->date,
                    $order->count
                ];
            });

        // Top products data
        $topProductsData = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [$product->name, $product->order_items_count];
            });

        return view('admin.dashboard', compact(
            'recentOrders',
            'categoryData',
            'orderTimelineData',
            'topProductsData'
        ));
    }
} 