<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * Get the user data by ID.
     *
     * @param int $userId
     * @return array
     */
    public function getUserData(int $userId): array
    {
        // Fetch the user data from the database or any other source
        // This is a placeholder for actual fetching logic
        $user = $this->fetchUser($userId);

        if (!$user) {
            return [
                'id' => $userId,
                'name' => null,
                'email' => null,
                'role' => null,
            ];
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }

    /**
     * Fetch the user by ID.
     *
     * @param int $userId
     * @return mixed
     */
    public function fetchUser(int $userId) : ?User
    {

        return User::findOrFail($userId); 

    }

    public function fetchAuthenticatedUser() : ?User
    {

        // Fetch the authenticated user
        $user = Auth::user();

        if (!$user) {
            return null; 
        }

        return $user;

    }
}