<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get user who try to get users in database and check if he is admin
        $user=$request->user();
        $user_roles=$user->roles;
        $isAdmin=false;
        foreach ($user_roles as $user_R){
            if($user_R['role']==='admin'){
                $isAdmin=true;
                break;
            }
        }

        if ($user && $isAdmin){
            return $next($request);
        }

        return response()->json([
            'message'=>__('you do not have permission to access this resource')
        ],404);
    }
}
