<?php

namespace App\Http\Middleware;

use Closure;

class RoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $userRole = $request->user()->role->name;
        if($userRole != $role) {
            return response()->json([
               'status' => false,
               'message' => [
                   'role' => 'У вас нет прав на эту операцию'
               ]
            ])->setStatusCode(400, 'Invalid role');
        }
        return $next($request);
    }
}
