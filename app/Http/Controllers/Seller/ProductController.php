<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('seller_id', Auth::id())
            ->latest('created_at')
            ->get();

        return view('seller.dashboard', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'picture' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'file' => 'nullable|file|max:20480',
        ]);

        $picturePath = $request->file('picture')->store('products/pictures', 'public');

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('products/files', 'public');
        }

        Product::create([
            'seller_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'picture_url' => $picturePath,
            'file_url' => $filePath,
            'status' => 'active',
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'محصول با موفقیت اضافه شد');
    }

    public function edit(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'file' => 'nullable|file|max:20480',
        ]);

        // اگر عکس جدید آپلود شد
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('products/pictures', 'public');
            $product->picture_url = $picturePath;
        }

        // اگر فایل جدید آپلود شد
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('products/files', 'public');
            $product->file_url = $filePath;
        }

        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->category_id = $request->category_id;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('seller.dashboard')->with('success', 'محصول با موفقیت ویرایش شد');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('seller.dashboard')->with('success', 'محصول با موفقیت حذف شد');
    }

    public function analytics()
    {
        $sellerId = Auth::id();

        $products = Product::where('seller_id', $sellerId)->get();

        $orders = Order::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })
            ->with('product')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $totalViews = Product::where('seller_id', $sellerId)
            ->sum('views');

        $totalSales = Product::where('seller_id', $sellerId)
            ->sum('sales_count');

        $totalRevenue = Order::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->where('status', 'paid')
            ->sum('amount');

        $thisMonth = Order::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $chartData = Product::where('seller_id', $sellerId)
            ->orderByDesc('sales_count')
            ->orderByDesc('views')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'title' => $p->title,
                'views' => $p->views,
                'sales' => $p->sales_count,
            ]);

        return view('seller.analytics', compact(
            'orders',
            'totalViews',
            'totalSales',
            'totalRevenue',
            'thisMonth',
            'chartData',
        ));
    }

    public function orders(Request $request)
    {
        $sellerId = Auth::id();


        $query = Order::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })
            ->with('product');


        if ($request->filled('search')) {

            $query->whereHas('product', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }


        if ($request->filled('from_date')) {

            $fromDate = $this->jalaliToGregorian($request->from_date);

            if ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            }
        }


        if ($request->filled('to_date')) {

            $toDate = $this->jalaliToGregorian($request->to_date);

            if ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }
        }


        $orders = $query
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();


        return view('seller.orders', compact('orders'));
    }
    private function jalaliToGregorian(string $jalaliDate): ?string
    {
        try {
            $normalized = strtr($jalaliDate, [
                '۰' => '0',
                '۱' => '1',
                '۲' => '2',
                '۳' => '3',
                '۴' => '4',
                '۵' => '5',
                '۶' => '6',
                '۷' => '7',
                '۸' => '8',
                '۹' => '9',
            ]);

            return \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $normalized)
                ->toCarbon()
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
