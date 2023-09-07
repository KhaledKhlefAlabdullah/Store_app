<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * @returns Api access token
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // validate inputs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // create user with inputs
        $user = User::create([
            'id' => Hash::make(now()->toString()),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // create event on user registered
        event(new Registered($user));
        // Login the new user
        Auth::login($user);
        // check if the request called json
        if($request->wantsJson()){
            //remove the tokens
            $user->tokens()->delete();
            /**
             * To determine the expiration time for Token
             * addDays : takes the number of days before the expiration of token
             * @returns \DateTime
             */
            $expiresAt = Carbon::now()->addDays(2);
            //create new token
            $token=$request->user()->createToken('register_token',['*'],$expiresAt);
            //return the token
            return response()->json([
                'token'=>$token->plainTextToken,
                'message'=>__('success')
            ],  200);
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
