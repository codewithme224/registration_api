<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\SchoolResource;
use App\Models\Package;
use App\Models\User;
use App\Traits\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   use AuditTrail;

    /**
     * Register New Users.
     * @param 
     */
    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Get the selected package
        $package = Package::findOrFail($data['package_id']);

        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'address' => $data['address'],
            'phone' => $data['phone'],
            'package_id' => $package->id,
            'logo' => $logoPath,
        ]);

        // Audit the user creation
        $this->logAudit('User created', $user->id, 'users', $user->toArray());

        return response()->success(new SchoolResource($user));
        

    }

    /**
     * Login user.
     * @param 
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            return response()->unprocessable('Invalid credentials', 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user details with token
        $data = [
            'token' => $token,
            'user' => $user
        ];

        // Audit the user login
        $this->logAudit('User logged in', $user->id, 'users', $user->toArray());
      
        return response()->success($data);
    }


    /**
     * Fetch the authenticated user.
     * @param Illuminate\Http\Request $request
     */

    public function userData(Request $request)
    {
       $user =  Auth::user();

       if (!$user) {
           return response()->unprocessable('User not found', 404);
        } 

        // Audit the user details fetch
        $this->logAudit('User details fetched', $user->id, 'users', $user->toArray());

        return response()->success($request->user());
    }
   

   

    /**
     * Logout User.
     */
    public function logout()
    {
        $user = Auth::user();

        if (!$user) {
            \Log::warning('Logout attempted for non-existent user');
            return response()->unprocessable( 'User not found', 404);
        }

        try {
            $user->currentAccessToken()->delete();
            \Log::info('User logged out successfully', ['user_id' => $user->id]);

            // Audit the user logout
            $this->logAudit('User logged out', $user->id, 'users', $user->toArray());

            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error during logout', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred during logout'], 500);
        }
    }


    /**
     * Reset Password.
     */
    public function resetPassword()
    {
        // Reset password logic
        


    }
}
