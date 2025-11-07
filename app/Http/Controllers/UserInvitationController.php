<?php

namespace App\Http\Controllers;

use App\Services\UserInvitationService;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

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
        // Only allow specific roles for invitations: teacher, registrar, head_teacher
        $roles = \Spatie\Permission\Models\Role::whereIn('name', UserInvitationService::getAllowedRoles())
            ->orderBy('name')
            ->get();
        return view('user-admin.invitations.invite', compact('roles'));
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
            'role' => 'required|in:' . implode(',', UserInvitationService::getAllowedRoles()),
            'contact_number' => 'nullable|string|max:20',
            'program_id' => 'required_if:role,teacher,head_teacher|exists:programs,id',
        ]);

        $result = $this->invitationService->sendInvitation($validated, $validated['role'], 1);

        if (!$result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['error']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', $result['message']);
    }

    /**
     * Show user management page
     */
    public function index()
    {
        return view('user-admin.users.index', [
            'roles' => $this->getAssignableRoles(),
        ]);
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
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'contact_number' => 'nullable|string|max:20',
                'role' => ['required', Rule::exists('roles', 'name')],
                'program_id' => 'required_if:role,teacher,head_teacher|exists:programs,id',
                'specialization' => 'nullable|string|max:255'
            ]);

            // Create user account
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt('temporary_password'), // Will be changed on first login
                'status' => 'Active',
                'invitation_data' => json_encode([
                    'contact_number' => $validated['contact_number'],
                ]),
            ]);

            // Assign role
            $user->assignRole($validated['role']);

            // Create teacher record if role is teacher or head_teacher
            if (in_array($validated['role'], ['teacher', 'head_teacher'])) {
                \App\Models\Teacher::create([
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'program_id' => $validated['program_id'],
                    'contact_number' => $validated['contact_number'],
                    'specialization' => $validated['specialization']
                ]);
            }

            // Audit logging
            \Log::info('User created', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $validated['role'],
                'created_by' => auth()->user()->id,
                'created_by_email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'role' => $validated['role']
                ]
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('User creation failed', [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create user: ' . $th->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        try {
            $userData = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'middle_name' => $user->middle_name,
                'email' => $user->email,
                'role' => $user->roles->first()?->name,
                'status' => $user->status,
            ];

            // Get additional data based on role
            if ($user->teacher) {
                $userData['program_id'] = $user->teacher->program_id;
                $userData['contact_number'] = $user->teacher->contact_number;
            } else {
                // Get from invitation_data for other roles
                $invitationData = json_decode($user->invitation_data, true);
                $userData['contact_number'] = $invitationData['contact_number'] ?? '';
            }

            return response()->json([
                'success' => true,
                'user' => $userData
            ]);
        } catch (\Throwable $th) {
            \Log::error('User show failed', [
                'error' => $th->getMessage(),
                'user_id' => $user->id,
                'requested_by' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to load user: ' . $th->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'contact_number' => 'nullable|string|max:20',
                'role' => ['required', Rule::exists('roles', 'name')],
                'program_id' => 'required_if:role,teacher,head_teacher|exists:programs,id',
            ]);

            // Update user basic info
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'email' => $validated['email'],
                'invitation_data' => json_encode([
                    'contact_number' => $validated['contact_number'],
                ]),
            ]);

            // Update role
            $user->syncRoles([$validated['role']]);

            // Update teacher record if role is teacher or head_teacher
            if (in_array($validated['role'], ['teacher', 'head_teacher'])) {
                if ($user->teacher) {
                    $user->teacher->update([
                        'program_id' => $validated['program_id'],
                        'contact_number' => $validated['contact_number'],
                    ]);
                } else {
                    \App\Models\Teacher::create([
                        'user_id' => $user->id,
                        'program_id' => $validated['program_id'],
                        'contact_number' => $validated['contact_number'],
                    ]);
                }
            } else {
                // Remove teacher record if role changed
                if ($user->teacher) {
                    $user->teacher->delete();
                }
            }

            // Audit logging
            \Log::info('User updated', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $validated['role'],
                'updated_by' => auth()->user()->id,
                'updated_by_email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'role' => $validated['role']
                ]
            ]);
        } catch (\Throwable $th) {
            \Log::error('User update failed', [
                'error' => $th->getMessage(),
                'user_id' => $user->id,
                'updated_by' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update user: ' . $th->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        try {
            // Check if user has any related data that would prevent deletion
            if ($user->student || $user->applicant) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cannot delete user with associated student or applicant records'
                ], 422);
            }

            $userEmail = $user->email;
            $userRole = $user->roles->first()?->name;

            // Delete related records
            if ($user->teacher) {
                $user->teacher->delete();
            }

            // Delete user
            $user->delete();

            // Audit logging
            \Log::info('User deleted', [
                'deleted_user_email' => $userEmail,
                'deleted_user_role' => $userRole,
                'deleted_by' => auth()->user()->id,
                'deleted_by_email' => auth()->user()->email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Throwable $th) {
            \Log::error('User deletion failed', [
                'error' => $th->getMessage(),
                'user_id' => $user->id,
                'deleted_by' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to delete user: ' . $th->getMessage()
            ], 422);
        }
    }

    /**
     * Get programs for dropdowns
     */
    public function getPrograms()
    {
        try {
            $programs = \App\Models\Program::select('id', 'name')->get();
            
            return response()->json([
                'success' => true,
                'programs' => $programs
            ]);
        } catch (\Throwable $th) {
            \Log::error('Get programs failed', [
                'error' => $th->getMessage(),
                'requested_by' => auth()->user()->id,
                'ip_address' => request()->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to load programs: ' . $th->getMessage()
            ], 500);
        }
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
     * Show roles management page
     */
    public function roles()
    {
        return view('user-admin.users.index', [
            'roles' => $this->getAssignableRoles(),
        ]);
    }

    /**
     * Get assignable roles for user management views
     */
    private function getAssignableRoles()
    {
        return Role::orderBy('name')->get();
    }

    /**
     * Get redirect route based on user role after registration
     */
    private function getRedirectRouteForRole(string $role): string
    {
        switch ($role) {
            case 'teacher':
                return 'teacher.dashboard';
            case 'head_teacher':
                return 'head-teacher.dashboard';
            case 'registrar':
                return 'registrar.dashboard';
            default:
                return 'dashboard';
        }
    }


    /**
     * Get analytics data for user management
     */
    public function getAnalytics()
    {
        try {
            // Get current date and dates for comparison
            $now = now();
            $lastWeek = $now->copy()->subWeek();
            $lastMonth = $now->copy()->subMonth();
            
            $analytics = [
                // Total system users
                'total_users' => \App\Models\User::count(),
                
                // User status breakdown
                'active_users' => \App\Models\User::where('status', 'active')->count(),
                'pending_invitations' => \App\Models\User::where('status', 'pending')->count(),
                'inactive_users' => \App\Models\User::where('status', 'inactive')->count(),
                
                // Role distribution
                'total_teachers' => \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'teacher');
                })->count(),
                'total_students' => \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'student');
                })->count(),
                'total_registrars' => \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'registrar');
                })->count(),
                'total_head_teachers' => \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'head_teacher');
                })->count(),
                
                // Recent activity
                'new_users_this_week' => \App\Models\User::where('created_at', '>=', $lastWeek)->count(),
                'new_users_this_month' => \App\Models\User::where('created_at', '>=', $lastMonth)->count(),
                
                // Invitation metrics
                'pending_invitations_this_week' => \App\Models\User::where('status', 'pending')
                    ->where('created_at', '>=', $lastWeek)->count(),
                'expired_invitations' => \App\Models\User::where('status', 'pending')
                    ->where('created_at', '<', $now->copy()->subDays(7))->count(),
                
                // System health metrics
                'users_without_roles' => \App\Models\User::whereDoesntHave('roles')->count(),
                'recently_active_users' => \App\Models\User::where('updated_at', '>=', $lastWeek)->count(),
            ];

            return response()->json([
                'success' => true,
                'analytics' => $analytics
            ]);
        } catch (\Exception $e) {
            \Log::error('getAnalytics error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to load analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all roles data for DataTables (AJAX)
     */
    public function getRolesData(Request $request)
    {
        try {
            $query = \Spatie\Permission\Models\Role::withCount('users');

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where('name', 'like', "%{$search}%");
            }

            // Handle sorting
            if ($request->has('order')) {
                $orderColumn = $request->input('order.0.column');
                $orderDir = $request->input('order.0.dir', 'asc');
                
                switch ($orderColumn) {
                    case 1: // Name column
                        $query->orderBy('name', $orderDir);
                        break;
                    case 2: // Permission count column - disable sorting
                        // Permission count sorting disabled to avoid AJAX errors
                        break;
                    case 3: // Users count column
                        $query->orderBy('users_count', $orderDir);
                        break;
                    default:
                        $query->orderBy('id', 'desc');
                        break;
                }
            } else {
                $query->orderBy('id', 'desc');
            }

            $total = \Spatie\Permission\Models\Role::count();
            $filtered = $query->count();

            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($role, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions_count' => $role->permissions()->count(),
                        'users_count' => $role->users_count,
                        'created_at' => $role->created_at->format('M d, Y')
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('getRolesData error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load roles data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all permissions with their categories
     */
    public function getAllPermissions()
    {
        try {
            $permissions = \App\Models\Permission::with('permissionCategory')->get();
            
            if ($permissions->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'permissions' => [],
                    'message' => 'No permissions found. Please run the seeder to create permissions.'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'permissions' => $permissions->map(function($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'category' => $permission->permissionCategory ? $permission->permissionCategory->category_name : 'General'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('getAllPermissions error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to load permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific role with its permissions
     */
    public function getRole($roleId)
    {
        try {
            $role = \Spatie\Permission\Models\Role::with(['permissions' => function($query) {
                $query->with('permissionCategory');
            }])->find($roleId);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'error' => 'Role not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description,
                    'permissions' => $role->permissions->map(function($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'category' => $permission->permissionCategory ? $permission->permissionCategory->category_name : 'General'
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getRole error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to load role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new role
     */
    public function createRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $role = \Spatie\Permission\Models\Role::create([
                'name' => $request->name,
            ]);

            // Assign permissions if provided
            if ($request->has('permissions')) {
                $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }

            // Log the activity
            activity('user_management')
                ->causedBy(auth()->user())
                ->performedOn($role)
                ->withProperties([
                    'action' => 'created',
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'assigned_permissions' => $request->permissions ?? [],
                    'permissions_count' => $role->permissions()->count(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Role created');

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            \Log::error('createRole error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to create role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update role name, description, and permissions
     */
    public function updateRole(Request $request, $roleId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $role = \Spatie\Permission\Models\Role::find($roleId);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'error' => 'Role not found'
                ], 404);
            }

            // Store original values for comparison
            $originalName = $role->name;
            $originalPermissions = $role->permissions()->pluck('id')->toArray();

            // Update role name
            $role->name = $request->name;
            $role->save();

            // Update permissions
            if ($request->has('permissions')) {
                $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            // Log the activity
            activity('user_management')
                ->causedBy(auth()->user())
                ->performedOn($role)
                ->withProperties([
                    'action' => 'updated',
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'original_name' => $originalName,
                    'name_changed' => $originalName !== $request->name,
                    'original_permissions' => $originalPermissions,
                    'new_permissions' => $request->permissions ?? [],
                    'permissions_changed' => $originalPermissions !== ($request->permissions ?? []),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Role updated');

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('updateRole error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request, $roleId)
    {
        try {
            \Log::info('updateRolePermissions called', [
                'roleId' => $roleId,
                'requestData' => $request->all(),
                'permissions' => $request->permissions
            ]);

            $role = \Spatie\Permission\Models\Role::find($roleId);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'error' => 'Role not found'
                ], 404);
            }

            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions)->get();
            \Log::info('Syncing permissions', [
                'roleId' => $roleId,
                'permissionIds' => $request->permissions,
                'permissionsFound' => $permissions->pluck('id')->toArray()
            ]);
            
            $role->syncPermissions($permissions);

            return response()->json([
                'success' => true,
                'message' => 'Role permissions updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('updateRolePermissions error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to update role permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a role
     */
    public function deleteRole($roleId)
    {
        try {
            $role = \Spatie\Permission\Models\Role::find($roleId);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'error' => 'Role not found'
                ], 404);
            }

            // Check if role has users
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cannot delete role that has assigned users'
                ], 400);
            }

            // Store role details before deletion
            $roleDetails = [
                'id' => $role->id,
                'name' => $role->name,
                'permissions_count' => $role->permissions()->count(),
                'permissions' => $role->permissions()->pluck('name')->toArray(),
                'users_count' => $role->users()->count()
            ];

            $role->delete();

            // Log the activity
            activity('user_management')
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'deleted',
                    'role_id' => $roleDetails['id'],
                    'role_name' => $roleDetails['name'],
                    'permissions_count' => $roleDetails['permissions_count'],
                    'permissions' => $roleDetails['permissions'],
                    'users_count' => $roleDetails['users_count'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Role deleted');

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('deleteRole error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        try {
            $roles = \Spatie\Permission\Models\Role::withCount('users')->get()->map(function($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description,
                    'users_count' => $role->users_count
                ];
            });

            return response()->json([
                'success' => true,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            \Log::error('getAllRoles error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to load roles: ' . $e->getMessage()
            ], 500);
        }
    }




    /**
     * Get all users data for DataTables (AJAX).
     */
    public function getAllUsers(Request $request)
    {
        try {
            $query = User::with(['roles', 'teacher', 'student']);

            // Search filter
            if ($search = $request->input('search.value')) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function($teacherQuery) use ($search) {
                        $teacherQuery->where('employee_id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('student', function($studentQuery) use ($search) {
                        $studentQuery->where('lrn', 'like', "%{$search}%");
                    });
                });
            }

            // Role filter
            if ($role = $request->input('role_filter')) {
                $query->whereHas('roles', function($roleQuery) use ($role) {
                    $roleQuery->where('name', $role);
                });
            }

            // Status filter
            if ($status = $request->input('status_filter')) {
                if ($status === 'active') {
                    $query->whereHas('teacher', function($teacherQuery) {
                        $teacherQuery->where('status', 'active');
                    });
                } elseif ($status === 'inactive') {
                    $query->whereHas('teacher', function($teacherQuery) {
                        $teacherQuery->where('status', 'inactive');
                    });
                } elseif ($status === 'invited') {
                    $query->whereNotNull('invitation_token');
                } elseif ($status === 'registered') {
                    $query->whereNull('invitation_token');
                }
            }

            $total = $query->count();
            $filtered = $total;

            // Handle sorting
            if ($request->has('order')) {
                $orderColumn = $request->input('order.0.column');
                $orderDir = $request->input('order.0.dir', 'asc');
                
                switch ($orderColumn) {
                    case 1: // Name column
                        $query->orderBy('first_name', $orderDir)
                              ->orderBy('last_name', $orderDir);
                        break;
                    case 2: // Email column
                        $query->orderBy('email', $orderDir);
                        break;
                    case 3: // Role column
                        $query->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                              ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                              ->orderBy('roles.name', $orderDir)
                              ->select('users.*'); // Ensure we only select user columns
                        break;
                    case 4: // Status column
                        // For status sorting, we need to create a custom order
                        $query->orderByRaw("
                            CASE 
                                WHEN invitation_token IS NOT NULL THEN 1
                                WHEN EXISTS(SELECT 1 FROM teachers WHERE teachers.user_id = users.id AND teachers.status = 'inactive') THEN 2
                                ELSE 3
                            END {$orderDir}
                        ");
                        break;
                    case 5: // Created column
                        $query->orderBy('created_at', $orderDir);
                        break;
                    default:
                        $query->orderBy('id', 'desc');
                        break;
                }
            } else {
                $query->orderBy('id', 'desc');
            }

            $start = $request->input('start', 0);

            $data = $query
                ->offset($start)
                ->limit($request->length)
                ->get()
                ->map(function ($user, $key) use ($start) {
                    $roles = $user->roles->pluck('name')->implode(', ');
                    $status = 'Registered';
                    $statusClass = 'bg-green-100 text-green-800';
                    
                    if ($user->invitation_token) {
                        $status = 'Invited';
                        $statusClass = 'bg-orange-100 text-orange-800';
                    } elseif ($user->teacher && $user->teacher->status === 'inactive') {
                        $status = 'Inactive';
                        $statusClass = 'bg-red-100 text-red-800';
                    }

                    return [
                        'index' => $start + $key + 1,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'roles' => $roles ?: 'No Role',
                        'status' => $status,
                        'status_class' => $statusClass,
                        'created_at' => $user->created_at->format('M d, Y'),
                        'id' => $user->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('getAllUsers error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load users data: ' . $e->getMessage()
            ], 500);
        }
    }
}
