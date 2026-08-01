<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if($user->status === 'inactive')
        {

            $allowed_routes = [
                'logout',
                'tickets.index',
                'tickets.create',
                'tickets.store',
                'tickets.show',
                'tickets.reply',
            ];

            if (!in_array($request->route()->getName(), $allowed_routes)) {
                return redirect()->route('tickets.index')
                ->with('error', 'حساب شما غیرفعال شده است. لطفاً از طریق تیکت پشتیبانی با ما در ارتباط باشید.');
            }
        }


        return $next($request);
    }
}
