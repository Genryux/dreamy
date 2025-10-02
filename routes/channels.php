<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channels for mobile app compatibility
Broadcast::channel('admins', function ($user) {
    return $user->hasRole(['registrar', 'super_admin']); // admin roles can listen
});

Broadcast::channel('teachers', function ($user) {
    return $user->hasRole(['head_teacher', 'teacher']); // teacher roles can listen
});

Broadcast::channel('students', function ($user) {
    return $user->hasRole(['student']); // student roles can listen (for mobile app)
});

// User-specific channels as public channels (for mobile app compatibility)
Broadcast::channel('user.{id}', function ($user, $id) {
    return true; // Make it truly public for mobile app - Laravel notifications will handle the targeting
});
