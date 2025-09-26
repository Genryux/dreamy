<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * Get the permission category associated with the permission.
     */
    public function permissionCategory()
    {
        return $this->hasOne(PermissionCategory::class);
    }
}
