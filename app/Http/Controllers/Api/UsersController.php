<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with(['roles'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        if (isset($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        // Log the activity
        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'action' => 'created',
                'user_id' => $user->id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_email' => $user->email,
                'assigned_roles' => $validated['roles'] ?? [],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('User created');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user->load(['roles'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $user->load(['roles'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        $updateData = [
            'first_name' => $validated['first_name'] ?? $user->first_name,
            'last_name' => $validated['last_name'] ?? $user->last_name,
            'email' => $validated['email'] ?? $user->email,
        ];

        if (isset($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Store original values for comparison
        $originalValues = $user->toArray();
        $originalRoles = $user->roles->pluck('name')->toArray();

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        // Log the activity
        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'action' => 'updated',
                'user_id' => $user->id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_email' => $user->email,
                'original_values' => $originalValues,
                'new_values' => $updateData,
                'changes' => array_diff_assoc($updateData, $originalValues),
                'original_roles' => $originalRoles,
                'new_roles' => $validated['roles'] ?? $originalRoles,
                'password_changed' => isset($validated['password']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('User updated');

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user->load(['roles'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // Store user details before deletion
        $userDetails = [
            'id' => $user->id,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray(),
            'created_at' => $user->created_at,
            'last_login' => $user->last_login_at
        ];

        $user->delete();

        // Log the activity
        activity('user_management')
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted',
                'user_id' => $userDetails['id'],
                'user_name' => $userDetails['name'],
                'user_email' => $userDetails['email'],
                'user_roles' => $userDetails['roles'],
                'user_created_at' => $userDetails['created_at'],
                'last_login' => $userDetails['last_login'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('User deleted');

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
} 