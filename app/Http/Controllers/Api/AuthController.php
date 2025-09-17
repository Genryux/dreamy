<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('mobile_app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login user (students only for mobile app)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        
        // Check if user is a student - mobile app is for students only
        if (!$user->student) {
            // Log out the user immediately since they're not a student
            Auth::logout();
            return response()->json([
                'message' => 'Access denied. Mobile app is only available for enrolled students.'
            ], 403);
        }

        $token = $user->createToken('mobile_app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'pin_required' => $user->pin_enabled && $user->pin,
            'pin_setup_required' => !$user->pin
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // Get current user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Change password
    public function changePassword(Request $request)
    {
        $validationRules = [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ];

        $user = $request->user();

        // Only require PIN validation if PIN is enabled
        if ($user->pin_enabled) {
            $validationRules['pin'] = 'required|string|size:6';
        }

        $request->validate($validationRules);

        // Verify PIN if PIN is enabled and provided
        if ($user->pin_enabled && $request->has('pin') && !Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 400);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    // Setup PIN for new users
    public function setupPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6|confirmed',
        ]);

        $user = $request->user();

        // Check if user already has a PIN
        if ($user->pin) {
            return response()->json(['message' => 'PIN already exists'], 400);
        }

        // Setup PIN
        $user->update([
            'pin' => Hash::make($request->pin),
            'pin_enabled' => true,
            'pin_setup_at' => now(),
        ]);

        return response()->json(['message' => 'PIN setup successfully']);
    }

    // Verify PIN after login
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        $user = $request->user();

        // Check if user has PIN enabled
        if (!$user->pin_enabled || !$user->pin) {
            return response()->json(['message' => 'PIN is not enabled'], 400);
        }

        // Verify PIN
        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 400);
        }

        return response()->json(['message' => 'PIN verified successfully']);
    }

    // Change PIN
    public function changePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'required|string|size:6',
            'pin' => 'required|string|size:6|confirmed',
        ]);

        $user = $request->user();

        // Check if user has PIN enabled
        if (!$user->pin_enabled || !$user->pin) {
            return response()->json(['message' => 'PIN is not enabled'], 400);
        }

        // Verify current PIN
        if (!Hash::check($request->current_pin, $user->pin)) {
            return response()->json(['message' => 'Current PIN is incorrect'], 400);
        }

        // Update PIN
        $user->update([
            'pin' => Hash::make($request->pin),
            'pin_setup_at' => now(),
        ]);

        return response()->json(['message' => 'PIN changed successfully']);
    }

    // Toggle PIN security
    public function togglePin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
            'enable' => 'required|boolean',
        ]);

        $user = $request->user();

        // Verify PIN if trying to disable
        if (!$request->enable && $user->pin_enabled && !Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 400);
        }

        // Update PIN enabled status
        $user->update([
            'pin_enabled' => $request->enable,
        ]);

        $message = $request->enable ? 'PIN security enabled' : 'PIN security disabled';
        return response()->json(['message' => $message]);
    }

    // Change email
    public function changeEmail(Request $request)
    {
        $validationRules = [
            'email' => 'required|email|unique:users,email',
        ];

        $user = $request->user();

        // Only require PIN validation if PIN is enabled
        if ($user->pin_enabled) {
            $validationRules['pin'] = 'required|string|size:6';
        }

        $request->validate($validationRules);

        // Verify PIN if PIN is enabled and provided
        if ($user->pin_enabled && $request->has('pin') && !Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN is incorrect'], 400);
        }

        // Update email
        $user->update([
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Email changed successfully']);
    }
}