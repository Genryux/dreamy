<?php

namespace App\Services;

use App\Mail\UserInvitationMail;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserInvitationService
{
    /**
     * Allowed roles for user invitations
     */
    const ALLOWED_ROLES = ['teacher', 'registrar', 'head_teacher'];

    /**
     * Get allowed roles for invitations
     */
    public static function getAllowedRoles(): array
    {
        return self::ALLOWED_ROLES;
    }

    /**
     * Send invitation to a user with specified role
     */
    public function sendInvitation(array $userData, string $role, int $invitedBy): array
    {
        try {
            DB::beginTransaction();

            // Validate role
            if (!in_array($role, self::ALLOWED_ROLES)) {
                return [
                    'success' => false,
                    'error' => 'Invalid role specified. Only teacher, registrar, and head_teacher roles are allowed for invitations.'
                ];
            }

            // Generate unique invitation token
            $invitationToken = Str::random(64);

            // Create user record with invitation data
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make(Str::random(32)), // Temporary password
                'invitation_token' => $invitationToken,
                'invitation_sent_at' => now(),
                'invitation_role' => $role,
                'invited_by' => $invitedBy,
                'status' => 'invited', // New status for invited users
                // Store teacher-specific data as JSON for later use
                'invitation_data' => ($role === 'teacher' || $role === 'head_teacher') ? json_encode([
                    'middle_name' => $userData['middle_name'] ?? null,
                    'contact_number' => $userData['contact_number'] ?? null,
                    'program_id' => $userData['program_id'] ?? null,
                ]) : null,
            ]);

            // Note: Role-specific records (like Teacher) will be created after successful registration
            // This follows the same pattern as the Applicant model

            // Send invitation email
            $this->sendInvitationEmail($user, $invitationToken, $role);

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'invitation_token' => $invitationToken,
                'role' => $role,
                'message' => 'Invitation sent successfully to ' . $userData['email']
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => 'Failed to send invitation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Accept invitation and complete user account setup
     */
    public function acceptInvitation(string $token, array $userData): array
    {
        try {
            DB::beginTransaction();

            // Find user by invitation token
            $user = User::where('invitation_token', $token)
                ->whereNull('invitation_accepted_at')
                ->first();

            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'Invalid or expired invitation token'
                ];
            }

            // Check if invitation is still valid (7 days)
            if ($user->invitation_sent_at->addDays(7)->isPast()) {
                return [
                    'success' => false,
                    'error' => 'Invitation has expired'
                ];
            }

            // Update user account with final password
            $user->update([
                'password' => Hash::make($userData['password']),
                'status' => 'active',
                'invitation_accepted_at' => now(),
                'invitation_token' => null, // Clear token after acceptance
            ]);

            // Assign role
            $user->assignRole($user->invitation_role);

            // Create role-specific records after successful registration
            if ($user->invitation_role === 'teacher' || $user->invitation_role === 'head_teacher') {
                $this->createTeacherRecord($user);
            }

            DB::commit();

            return [
                'success' => true,
                'user' => $user->fresh(),
                'role' => $user->invitation_role,
                'message' => 'Account created successfully'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => 'Failed to accept invitation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate invitation token
     */
    public function validateInvitation(string $token): array
    {
        $user = User::where('invitation_token', $token)
            ->whereNull('invitation_accepted_at')
            ->first();

        if (!$user) {
            return [
                'valid' => false,
                'error' => 'Invalid invitation token'
            ];
        }

        // Check if invitation is still valid (7 days)
        if ($user->invitation_sent_at->addDays(7)->isPast()) {
            return [
                'valid' => false,
                'error' => 'Invitation has expired'
            ];
        }

        return [
            'valid' => true,
            'user' => $user,
            'role' => $user->invitation_role,
            'expires_at' => $user->invitation_sent_at->addDays(7)
        ];
    }

    /**
     * Resend invitation
     */
    public function resendInvitation(int $userId): array
    {
        try {
            $user = User::findOrFail($userId);

            if ($user->invitation_accepted_at) {
                return [
                    'success' => false,
                    'error' => 'Invitation has already been accepted'
                ];
            }

            // Generate new token
            $newToken = Str::random(64);
            
            $user->update([
                'invitation_token' => $newToken,
                'invitation_sent_at' => now(),
            ]);

            // Send invitation email
            $this->sendInvitationEmail($user, $newToken, $user->invitation_role);

            return [
                'success' => true,
                'user' => $user,
                'invitation_token' => $newToken,
                'message' => 'Invitation resent successfully'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to resend invitation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cancel invitation
     */
    public function cancelInvitation(int $userId): array
    {
        try {
            $user = User::findOrFail($userId);

            if ($user->invitation_accepted_at) {
                return [
                    'success' => false,
                    'error' => 'Cannot cancel accepted invitation'
                ];
            }

            // Delete the user record if they haven't accepted yet
            $user->delete();

            return [
                'success' => true,
                'message' => 'Invitation cancelled successfully'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to cancel invitation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get pending invitations
     */
    public function getPendingInvitations()
    {
        return User::whereNotNull('invitation_token')
            ->whereNull('invitation_accepted_at')
            ->where('invitation_sent_at', '>', now()->subDays(7))
            ->orderBy('invitation_sent_at', 'desc')
            ->get();
    }

    /**
     * Get expired invitations
     */
    public function getExpiredInvitations()
    {
        return User::whereNotNull('invitation_token')
            ->whereNull('invitation_accepted_at')
            ->where('invitation_sent_at', '<=', now()->subDays(7))
            ->orderBy('invitation_sent_at', 'desc')
            ->get();
    }

    /**
     * Clean up expired invitations
     */
    public function cleanupExpiredInvitations(): int
    {
        return User::whereNotNull('invitation_token')
            ->whereNull('invitation_accepted_at')
            ->where('invitation_sent_at', '<=', now()->subDays(7))
            ->delete();
    }

    /**
     * Create teacher record for invited teacher
     */
    private function createTeacherRecord(User $user): void
    {
        // Get stored invitation data
        $invitationData = $user->invitation_data ? json_decode($user->invitation_data, true) : [];
        
        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => Teacher::generateEmployeeId(),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $invitationData['middle_name'] ?? null,
            'email_address' => $user->email,
            'contact_number' => $invitationData['contact_number'] ?? null,
            'program_id' => $invitationData['program_id'] ?? null,
            'status' => 'active', // Teacher record created after successful registration
        ]);
    }


    /**
     * Send invitation email
     */
    private function sendInvitationEmail(User $user, string $token, string $role): void
    {
        // TODO: Uncomment when email is configured
        Mail::to($user->email)->send(new UserInvitationMail($user, $token, $role));
        
        // For now, just log the invitation
        \Log::info("User invitation sent to {$user->email} with token: {$token} for role: {$role}");
        \Log::info("Registration URL: " . route('user.register', $token));
    }
}
