<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_id',
        'category_name',
        'description',
    ];

    /**
     * Get the permission that owns the category.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Get all unique category names.
     */
    public static function getUniqueCategories()
    {
        return self::distinct()
            ->orderBy('category_name')
            ->pluck('category_name')
            ->toArray();
    }

    /**
     * Get categories with their descriptions.
     */
    public static function getCategoriesWithDescriptions()
    {
        return self::select('category_name')
            ->selectRaw('MIN(description) as description')
            ->groupBy('category_name')
            ->orderBy('category_name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category_name,
                    'description' => $item->description
                ];
            })
            ->toArray();
    }
}