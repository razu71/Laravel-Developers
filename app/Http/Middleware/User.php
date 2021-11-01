<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->user_role == USER){
            return $next($request);
        }else{
            return sendResponseError('404','Permission denied');
        }
    }
}
