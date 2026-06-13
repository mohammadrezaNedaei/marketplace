<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ExploreController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('explore', compact('categories'));
    }

    public function products(Request $request)
    {
        $query = Product::query()
            ->where('status', 'active')
            ->with(['seller:id,username', 'category:id,name']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        match ($request->input('sort', 'newest')) {
            'popular'    => $query->orderBy('views', 'desc'),
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $paginated = $query->paginate(10);

        return response()->json([
            'data' => $paginated->map(fn($p) => [
                'id' => $p->id,
                'title'=> $p->title,
                'seller' => $p->seller->username ?? '',
                'category' => $p->category->name ?? '',
                'category_id' => $p->category_id,
                'price' => $p->price,
                'discount_price' => $p->discount_price,
                'views' => $p->views,
                'picture_url' => asset('storage/' . $p->picture_url),
                'url' => route('products.show', $p->id),
            ]),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'total'        => $paginated->total(),
        ]);
    }
}
