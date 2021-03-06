<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Mmex\MmexConstants;
use Auth;
use Closure;

class MmexEnsureGuid
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $guid = $request->input('guid');
        $user = User::where('mmex_guid', $guid)->first();

        if ($user && $guid == $user->mmex_guid) {
            // login user on api guard (simple alternative login method)
            Auth::guard('api')->setUser($user);

            return $next($request);
        }

        return response(MmexConstants::$wrong_guid)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
