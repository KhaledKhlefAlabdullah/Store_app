<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @returns json if call form api rout
     * @returns redirect_to_home_page if the call from web route
     */

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        // check if api call
        if ($request->expectsJson()) {
            // delete all tokens was used
            $request->user()->tokens()->delete();

            /**
             * To determine the expiration time for Token
             * addDays : takes the number of days before the expiration of token
             * @returns \DateTime
             */
            $expiresAt = Carbon::now()->addDays(2);
            $user=$request->user();
            // create new token
            $token = $user->createToken('login_token',['*'],$expiresAt);

            return response()->json([
                'token' => $token->plainTextToken
            ,
                'message' => __('login successfully')
            ], 200);
        }

        /**
         * @uses web the sessions it uses in web not with api so I put it after api call
         */
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session and tokens
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        // Check if response want json
        if($request->expectsJson()){
            // Delete user tokens
            $state=$request->user()->currentAccessToken()->delete();
            // Check if don successfully or not
            if(!$state){
                return response()->json([
                    'message'=>'logout failed'
                ],550);
            }
            return response()->json([
                'message'=>'logout successfully'
            ],200);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
