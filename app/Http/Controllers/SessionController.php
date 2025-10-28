<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{

    public function create()
    {

        return view('auth.session.create');
    }

    public function store()
    {

        $attributes = request()->validate([

            'email' => ['required', 'email'],
            'password' => ['required']

        ]);

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'password' => 'Those credentials don\'t match'
            ]);
        }

        request()->session()->regenerate();

        session()->forget('url.intended');

        // Get the authenticated user
        $user = Auth::user();

        // Check PIN requirements first
        if (!$user->pin) {
            // User doesn't have a PIN - redirect to setup
            return redirect()->route('auth.pin.setup');
        } elseif ($user->pin_enabled) {
            // User has PIN enabled - redirect to verification
            return redirect()->route('auth.pin.verify');
        }

        // PIN is disabled, proceed with normal role-based redirect
        // Mark PIN as verified since it's disabled
        request()->session()->put('pin_verified', true);

        // Detect platform (desktop vs web)
        $userAgent = request()->header('User-Agent');
        $isDesktop = str_contains($userAgent, 'Electron') || 
                     str_contains($userAgent, 'DreamyDesktopApp') ||
                     request()->hasHeader('X-Electron-App');

        // Check roles and redirect accordingly
        if ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('head_teacher')) {
            return redirect()->route('head-teacher.dashboard');
        } elseif ($user->hasRole('applicant')) {
            return redirect()->route('admission.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('admission.dashboard');
        } elseif ($user->hasRole(['registrar', 'super_admin'])) {
            // Temporary: Allow admin access from both platforms
            return redirect()->route('admin');
        }

        // Default redirect if no specific role is matched
        return redirect('/');
    }

    public function destroy(Request $request)
    {
        // Log the logout action for security audit
        \Log::info('User logout', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        // Invalidate the session
        Auth::logout();
        
        // Regenerate session ID to prevent session fixation attacks
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear any cached user data
        $request->session()->flush();

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }

    public function showPinSetup()
    {
        $user = Auth::user();
        
        // If user already has a PIN, redirect to dashboard
        if ($user->pin) {
            return redirect()->route('dashboard');
        }

        return view('auth.pin-setup');
    }

    public function setupPin(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'new_pin' => ['required', 'string', 'size:6', 'confirmed', 'regex:/^[0-9]{6}$/'],
        ]);

        // Check for weak PINs during setup
        if ($this->isWeakPin($validated['new_pin'])) {
            return back()->withErrors(['new_pin' => 'This PIN is too common and not secure. Please choose a more unique PIN.']);
        }

        // Setup new PIN
        $user->update([
            'pin' => Hash::make($validated['new_pin']),
            'pin_enabled' => true,
            'pin_setup_at' => now(),
        ]);

        // Mark PIN as verified in session
        $request->session()->put('pin_verified', true);
        $request->session()->put('pin_verified_at', now());

        // Log the PIN setup for security audit
        \Log::info('User PIN setup during login', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return $this->redirectToDashboard()->with('success', 'Security PIN setup successfully!');
    }

    public function showPinVerification()
    {
        $user = Auth::user();
        
        // If user doesn't have a PIN or PIN is disabled, redirect to setup
        if (!$user->pin || !$user->pin_enabled) {
            return redirect()->route('auth.pin.setup');
        }

        // If PIN is already verified in this session, redirect to dashboard
        if (session('pin_verified', false)) {
            return $this->redirectToDashboard();
        }

        return view('auth.pin-verification');
    }

    public function verifyPin(Request $request)
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        // Enhanced rate limiting
        $rateLimitKey = "pin_verify_{$user->id}_{$ipAddress}";
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            
            \Log::warning('PIN verification rate limit exceeded', [
                'user_id' => $user->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'seconds_remaining' => $seconds,
                'timestamp' => now()
            ]);
            
            return back()->withErrors(['pin' => "Too many attempts. Please try again in {$seconds} seconds."]);
        }

        // Session-based attempt tracking
        $attempts = session('pin_attempts', 0);
        if ($attempts >= 3) {
            \Log::warning('PIN verification session attempts exceeded', [
                'user_id' => $user->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'timestamp' => now()
            ]);
            
            return back()->withErrors(['pin' => 'Too many failed attempts. Please log out and try again.']);
        }

        $validated = $request->validate([
            'pin' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ]);

        // Verify PIN
        if (!Hash::check($validated['pin'], $user->pin)) {
            $attempts++;
            session(['pin_attempts' => $attempts]);
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes
            
            \Log::warning('Failed PIN verification attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'attempts' => $attempts,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'timestamp' => now()
            ]);

            return back()->withErrors(['pin' => 'Invalid PIN. Attempts: ' . $attempts . '/3']);
        }

        // PIN is correct - clear attempts and mark as verified
        session()->forget('pin_attempts');
        RateLimiter::clear($rateLimitKey);
        $request->session()->put('pin_verified', true);
        $request->session()->put('pin_verified_at', now());
        
        // Regenerate session ID for additional security
        $request->session()->regenerate();

        // Log successful PIN verification
        \Log::info('Successful PIN verification', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'timestamp' => now()
        ]);

        return $this->redirectToDashboard()->with('success', 'PIN verified successfully!');
    }

    /**
     * Check if PIN is weak/common
     */
    private function isWeakPin(string $pin): bool
    {
        $weakPins = [
            '000000', '111111', '222222', '333333', '444444', '555555',
            '666666', '777777', '888888', '999999', '123456', '654321',
            '012345', '543210', '111222', '222333', '333444', '444555',
            '555666', '666777', '777888', '888999', '999000', '000111'
        ];
        
        return in_array($pin, $weakPins);
    }

    /**
     * Redirect user to appropriate dashboard based on their role
     */
    private function redirectToDashboard()
    {
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('head_teacher')) {
            return redirect()->route('head-teacher.dashboard');
        } elseif ($user->hasRole('applicant')) {
            return redirect()->route('admission.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('admission.dashboard');
        } elseif ($user->hasRole(['registrar', 'super_admin'])) {
            return redirect()->route('admin');
        }

        // Default redirect if no specific role is matched
        return redirect('/');
    }
}
