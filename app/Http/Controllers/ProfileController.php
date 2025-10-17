<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user-admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'pin_verification' => ['nullable', 'required_with:password', 'string', 'size:6'],
        ]);

        // Verify PIN if changing password and PIN is enabled
        if ($request->filled('password') && $user->pin_enabled) {
            if (!Hash::check($validated['pin_verification'], $user->pin)) {
                return back()->withErrors(['pin_verification' => 'The PIN is incorrect.']);
            }
        }

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Update user data
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => $request->filled('password') ? Hash::make($validated['password']) : $user->password,
        ]);

        // Log the profile update for security audit
        \Log::info('User profile updated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function setupPin(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'new_pin' => ['required', 'string', 'size:6', 'confirmed'],
        ]);

        // Setup new PIN
        $user->update([
            'pin' => Hash::make($validated['new_pin']),
            'pin_enabled' => true,
            'pin_setup_at' => now(),
        ]);

        // Log the PIN setup for security audit
        \Log::info('User PIN setup', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return redirect()->back()->with('success', 'PIN setup successfully.');
    }

    public function updatePin(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_pin' => ['required', 'string', 'size:6'],
            'new_pin' => ['required', 'string', 'size:6', 'confirmed'],
        ]);

        // Verify current PIN
        if (!Hash::check($validated['current_pin'], $user->pin)) {
            return back()->withErrors(['current_pin' => 'The current PIN is incorrect.']);
        }

        // Update PIN
        $user->update([
            'pin' => Hash::make($validated['new_pin']),
            'pin_setup_at' => now(),
        ]);

        // Log the PIN update for security audit
        \Log::info('User PIN updated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return redirect()->back()->with('success', 'PIN updated successfully.');
    }

    public function disablePin(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_pin' => ['required', 'string', 'size:6'],
        ]);

        // Verify current PIN
        if (!Hash::check($validated['current_pin'], $user->pin)) {
            return back()->withErrors(['current_pin' => 'The current PIN is incorrect.']);
        }

        // Disable PIN (keep the PIN stored, just disable it)
        $user->update([
            'pin_enabled' => false,
        ]);

        // Clear PIN verification from session since PIN is now disabled
        $request->session()->forget('pin_verified');

        // Log the PIN disable for security audit
        \Log::info('User PIN disabled', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return redirect()->back()->with('success', 'PIN disabled successfully. You can re-enable it anytime.');
    }

    public function enablePin(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_pin' => ['required', 'string', 'size:6'],
        ]);

        // Verify current PIN (PIN should still be stored)
        if (!Hash::check($validated['current_pin'], $user->pin)) {
            return back()->withErrors(['current_pin' => 'The current PIN is incorrect.']);
        }

        // Enable PIN
        $user->update([
            'pin_enabled' => true,
        ]);

        // Mark PIN as verified in session since user just entered it correctly
        $request->session()->put('pin_verified', true);

        // Log the PIN enable for security audit
        \Log::info('User PIN enabled', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return redirect()->back()->with('success', 'PIN enabled successfully.');
    }
}
