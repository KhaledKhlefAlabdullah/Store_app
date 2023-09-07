<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Mockery\Exception;

class UsersController extends Controller
{
    /**
     * Get all users in database
     * @return JsonResponse return all users in database
     */
    public function index(Request $request)
    {

       $users=User::all();

        if($users->isEmpty()){

            return response()->json([
                'message'=>__('failed to get users')
            ],505);

        }

        return response()->json([
            'users'=>$users,
            'message'=>__('successfully')
        ],200);

    }

    /**
     * Update the user data.
     * @return JsonResponse
     */
    public function update(Request $request, string $id)
    {
        // Handling the process
        try {
            // Validate inputs
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
                'profile_img' => ['image', 'max:2048'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Get the user or return a 404 response if not found
            $user = User::findOrFail($id);

            // Handle the image upload if provided
            if ($request->hasFile('profile_img')) {
                $file_extension = $request->file('profile_img')->getClientOriginalExtension();
                $file_name = Hash::make(now()) . '.' . $file_extension;
                $path = 'images\profile_images';
                $request->file('profile_img')->move(public_path($path), $file_name);
                $user->profile_img = $path . '\\' . $file_name;
            }

            // Update user with inputs
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $state = $user->save();

            if ($state) {
                return response()->json(['message' => __('updated successfully')], 200);
            } else {
                return response()->json(['message' => __('updated failed')], 500);
            }

        }catch (Exception $e){
            return response()->json([
                'message' => __('database connection error')
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
