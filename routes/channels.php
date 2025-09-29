<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admins', function ($user) {
    return $user->hasRole(['registrar', 'super_admin']); // admin roles can listen
});

Broadcast::channel('teachers', function ($user) {
    return $user->hasRole(['head_teacher', 'teacher']); // teacher roles can listen
});
