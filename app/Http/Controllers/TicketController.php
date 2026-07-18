<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // لیست تیکت‌های کاربر
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
                                ->latest('created_at')
                                ->get();

        return view('tickets.index', compact('tickets'));
    }

    // فرم ایجاد تیکت جدید
    public function create()
    {
        return view('tickets.create');
    }

    // ذخیره تیکت جدید
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'status'  => 'open',
        ]);

        // اولین پیام را هم ذخیره کن
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        return redirect()->route('tickets.show', $ticket)
                         ->with('success', 'تیکت با موفقیت ارسال شد');
    }

    // نمایش تیکت و پیام‌ها
    public function show(SupportTicket $ticket)
    {
        // فقط صاحب تیکت می‌تواند آن را ببیند
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['messages.sender']);

        return view('tickets.show', compact('ticket'));
    }

    // ارسال پیام جدید در تیکت
    public function reply(Request $request, SupportTicket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status === 'closed') {
            return back()->with('error', 'این تیکت بسته شده است');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        // وقتی کاربر پیام می‌دهد تیکت دوباره open می‌شود
        $ticket->status = 'open';
        $ticket->save();

        return back()->with('success', 'پیام ارسال شد');
    }
}