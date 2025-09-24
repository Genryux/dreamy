<?php

namespace App\Http\Controllers;

use App\Services\UserInvitationService;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserInvitationController extends Controller
{
    protected $invitationService;

    public function __construct(UserInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Show invitation form
     */
    public function invite()
    {
        return view('user-admin.invitations.invite');
    }

    /**
     * Send invitation
     */
    public function sendInvitation(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:teacher,registrar,head_teacher',
            'contact_number' => 'nullable|string|max:20',
            'program_id' => 'required_if:role,teacher,head_teacher|exists:programs,id',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);

        $result = $this->invitationService->sendInvitation($validated, $validated['role'], 1);

        if (!$result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['error']);
        }

        return redirect()->route('admin.invitations.index')
            ->with('success', $result['message']);
    }

    /**
     * Show pending invitations
     */
    public function index()
    {
        return view('user-admin.invitations.index');
    }

    /**
     * Get pending invitations for DataTables
     */
    public function getPendingInvitations()
    {
        $invitations = $this->invitationService->getPendingInvitations();

        return response()->json([
            'invitations' => $invitations
        ]);
    }

    /**
     * Resend invitation
     */
    public function resendInvitation(User $user)
    {
        $result = $this->invitationService->resendInvitation($user->id);

        if (!$result['success']) {
            return redirect()->back()
                ->with('error', $result['error']);
        }

        return redirect()->back()
            ->with('success', $result['message']);
    }

    /**
     * Cancel invitation
     */
    public function cancelInvitation(User $user)
    {
        $result = $this->invitationService->cancelInvitation($user->id);

        if (!$result['success']) {
            return redirect()->back()
                ->with('error', $result['error']);
        }

        return redirect()->back()
            ->with('success', $result['message']);
    }

    /**
     * Show user registration form
     */
    public function showRegistration(Request $request, string $token)
    {
        // Validate invitation token
        $validation = $this->invitationService->validateInvitation($token);
        
        if (!$validation['valid']) {
            return view('auth.invitation.invalid', [
                'error' => $validation['error']
            ]);
        }

        $user = $validation['user'];
        
        return view('auth.invitation.register', [
            'user' => $user,
            'token' => $token,
            'role' => $user->invitation_role,
            'expires_at' => $validation['expires_at']
        ]);
    }

    /**
     * Process user registration
     */
    public function storeRegistration(Request $request, string $token)
    {
        $validated = $request->validate([
            'password' => ['required', Password::min(8)->max(60)->letters()->numbers(), 'confirmed'],
            'terms' => 'required|accepted',
        ]);

        // Accept invitation and create account
        $result = $this->invitationService->acceptInvitation($token, $validated);

        if (!$result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['error']);
        }

        // Auto-login the new user
        Auth::login($result['user']);

        // Redirect based on role
        $redirectRoute = $this->getRedirectRouteForRole($result['role']);

        return redirect()->route($redirectRoute)
            ->with('success', 'Welcome! Your account has been created successfully.');
    }

    /**
     * Show invitation status page
     */
    public function status(Request $request, string $token)
    {
        $validation = $this->invitationService->validateInvitation($token);
        
        return view('auth.invitation.status', [
            'valid' => $validation['valid'],
            'user' => $validation['user'] ?? null,
            'error' => $validation['error'] ?? null,
            'expires_at' => $validation['expires_at'] ?? null,
            'token' => $token
        ]);
    }

    /**
     * Get redirect route based on user role
     */
    private function getRedirectRouteForRole(string $role): string
    {
        return match($role) {
            'teacher' => 'teacher.dashboard',
            'head_teacher' => 'head-teacher.dashboard',
            'registrar' => 'admin.dashboard',
            default => 'login'
        };
    }
}
