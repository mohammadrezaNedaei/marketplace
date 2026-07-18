<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // داشبورد ادمین
    public function index()
    {
        $totalUsers    = User::count();
        $totalSellers  = User::where('role', 'seller')->count();
        $totalBuyers   = User::where('role', 'buyer')->count();
        $openTickets   = SupportTicket::where('status', 'open')->count();
        $totalProducts = \App\Models\Product::where('status', 'active')->count();
        // آخرین فعالیت‌های پلتفرم
        $recentActivities = collect()
            ->merge(
                User::latest('created_at')->take(5)->get()->map(fn($u) => [
                    'type'       => 'user',
                    'icon'       => '👤',
                    'text'       => $u->username . ' ثبت‌نام کرد',
                    'created_at' => $u->created_at,
                ])
            )
            ->merge(
                \App\Models\Order::with('user')->latest('created_at')->take(5)->get()->map(fn($o) => [
                    'type'       => 'order',
                    'icon'       => '🛍',
                    'text'       => ($o->user->username ?? '—') . ' یک سفارش ثبت کرد',
                    'created_at' => $o->created_at,
                ])
            )
            ->merge(
                \App\Models\Product::with('seller')->latest('created_at')->take(5)->get()->map(fn($p) => [
                    'type'       => 'product',
                    'icon'       => '📦',
                    'text'       => ($p->seller->username ?? '—') . ' محصول جدید اضافه کرد: ' . $p->title,
                    'created_at' => $p->created_at,
                ])
            )
            ->merge(
                SupportTicket::with('user')->latest('created_at')->take(5)->get()->map(fn($t) => [
                    'type'       => 'ticket',
                    'icon'       => '🎫',
                    'text'       => ($t->user->username ?? '—') . ' تیکت جدید باز کرد',
                    'created_at' => $t->created_at,
                ])
            )
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalBuyers',
            'openTickets',
            'totalProducts',
            'recentActivities',
        ));
    }

    // لیست کاربران
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest('created_at')->paginate(20);

        return view('admin.users', compact('users'));
    }

    // ویرایش کاربر
    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    // ذخیره تغییرات کاربر
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:buyer,seller,admin',
            'password' => 'nullable|string|min:6',
        ]);

        $user->username = $request->username;
        $user->phone    = $request->phone;
        $user->role     = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'اطلاعات کاربر با موفقیت ویرایش شد');
    }

    // حذف کاربر
    public function deleteUser(User $user)
    {
        // ادمین نمی‌تواند خودش را حذف کند
        if ($user->id === Auth::id()) {
            return back()->with('error', 'نمی‌توانید حساب خود را حذف کنید');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'کاربر با موفقیت حذف شد');
    }

    // لیست تیکت‌ها
    public function tickets(Request $request)
    {
        $query = SupportTicket::with('user')->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(20);

        return view('admin.tickets', compact('tickets'));
    }

    // نمایش تیکت و پیام‌ها
    public function showTicket(SupportTicket $ticket)
    {
        $ticket->load(['user', 'messages.sender']);
        return view('admin.show-ticket', compact('ticket'));
    }

    // پاسخ به تیکت
    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        // وضعیت تیکت را به answered تغییر بده
        $ticket->status = 'answered';
        $ticket->save();

        return back()->with('success', 'پاسخ با موفقیت ارسال شد');
    }

    // تغییر وضعیت تیکت
    public function updateTicketStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,answered,closed',
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        return back()->with('success', 'وضعیت تیکت بروزرسانی شد');
    }

    // لیست همه محصولات
    public function products(Request $request)
    {
        $query = \App\Models\Product::with(['seller', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest('created_at')->paginate(20);

        return view('admin.products', compact('products'));
    }

    // ویرایش محصول توسط ادمین
    public function editProduct(\App\Models\Product $product)
    {
        $categories = \App\Models\Category::all();
        return view('admin.edit-product', compact('product', 'categories'));
    }

    // ذخیره تغییرات محصول توسط ادمین
    public function updateProduct(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id'    => 'required|exists:categories,id',
            'status'         => 'required|in:active,inactive',
        ]);

        $product->title          = $request->title;
        $product->description    = $request->description;
        $product->price          = $request->price;
        $product->discount_price = $request->discount_price;
        $product->category_id    = $request->category_id;
        $product->status         = $request->status;
        $product->save();

        return redirect()->route('admin.products')
            ->with('success', 'محصول با موفقیت ویرایش شد');
    }

    // حذف محصول توسط ادمین
    public function deleteProduct(\App\Models\Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')
            ->with('success', 'محصول با موفقیت حذف شد');
    }
}
