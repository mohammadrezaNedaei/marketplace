<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
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
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id'    => 'required|exists:categories,id',
            'picture'        => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'file'           => 'nullable|file|max:20480',
        ]);

        $picturePath = $request->file('picture')->store('products/pictures', 'public');

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('products/files', 'public');
        }

        Product::create([
            'seller_id'      => Auth::id(),
            'category_id'    => $request->category_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'price'          => $request->price,
            'discount_price' => $request->discount_price,
            'picture_url'    => $picturePath,
            'file_url'       => $filePath,
            'status'         => 'active',
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
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id'    => 'required|exists:categories,id',
            'picture'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'file'           => 'nullable|file|max:20480',
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

        $product->title          = $request->title;
        $product->description    = $request->description;
        $product->price          = $request->price;
        $product->discount_price = $request->discount_price;
        $product->category_id    = $request->category_id;

        $product->save();

        return redirect()->route('seller.dashboard')->with('success', 'محصول با موفقیت ویرایش شد');
    }

    // حذف محصول
    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('seller.dashboard')->with('success', 'محصول با موفقیت حذف شد');
    }
}
