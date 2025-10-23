<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use function activity;

class DiscountController extends Controller
{


    /**
     * Get discounts for DataTable
     */
    public function getDiscounts(Request $request)
    {
        try {

            $query = Discount::query();

            // Search functionality
            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Type filter
            if ($type = $request->input('type_filter')) {
                if ($type !== '') {
                    $query->where('discount_type', $type);
                }
            }

            // Status filter
            if ($status = $request->input('status_filter')) {
                if ($status !== '') {
                    $query->where('is_active', $status === 'active' ? 1 : 0);
                }
            }

            // Get total count
            $totalRecords = Discount::count();
            $filteredRecords = $query->count();

            // Pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);

            // Get data
            $data = $query
                ->orderBy('created_at', 'desc')
                ->offset($start)
                ->limit($length)
                ->get()
                ->map(function ($item, $key) use ($start) {
                    return [
                        'index' => $start + $key + 1,
                        'name' => $item->name,
                        'description' => $item->description,
                        'discount_type' => $item->discount_type,
                        'discount_value' => $item->discount_type === 'percentage'
                            ? $item->discount_value . '%'
                            : '₱ ' . number_format($item->discount_value, 2),
                        'is_active' => $item->is_active,
                        'id' => $item->id
                    ];
                });

            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('getDiscounts error: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created discount
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'is_active' => 'nullable'
        ]);

        // Additional validation for percentage discounts
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Percentage discount cannot exceed 100%'
            ], 422);
        }

        // Handle is_active checkbox properly
        $validated['is_active'] = $request->has('is_active');

        $discount = Discount::create($validated);

        activity('financial_management')
            ->causedBy(auth()->user())
            ->performedOn($discount)
            ->withProperties([
                'action' => 'created',
                'discount_id' => $discount->id,
                'discount_name' => $discount->name,
                'discount_description' => $discount->description,
                'discount_type' => $discount->discount_type,
                'discount_value' => $discount->discount_value,
                'is_active' => $discount->is_active,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Discount created');

        return response()->json([
            'success' => true,
            'message' => 'Discount created successfully.'
        ]);
    }

    /**
     * Update the specified discount
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'is_active' => 'nullable'
        ]);

        // Additional validation for percentage discounts
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Percentage discount cannot exceed 100%'
            ], 422);
        }

        // Handle is_active checkbox properly
        $validated['is_active'] = $request->has('is_active');

        // Store original values for comparison
        $originalValues = $discount->toArray();

        $discount->update($validated);

        // Log the activity
        activity('financial_management')
            ->causedBy(auth()->user())
            ->performedOn($discount)
            ->withProperties([
                'action' => 'updated',
                'discount_id' => $discount->id,
                'discount_name' => $discount->name,
                'original_values' => $originalValues,
                'new_values' => $validated,
                'changes' => array_diff_assoc($validated, $originalValues),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('Discount updated');

        return response()->json([
            'success' => true,
            'message' => 'Discount updated successfully.'
        ]);
    }

    /**
     * Remove the specified discount
     */
    public function destroy(Discount $discount)
    {
        // Store discount details before deletion
        $discountDetails = [
            'id' => $discount->id,
            'name' => $discount->name,
            'description' => $discount->description,
            'discount_type' => $discount->discount_type,
            'discount_value' => $discount->discount_value,
            'is_active' => $discount->is_active
        ];

        $discount->delete();

        // Log the activity
        activity('financial_management')
            ->causedBy(auth()->user())
            ->withProperties([
                'action' => 'deleted',
                'discount_id' => $discountDetails['id'],
                'discount_name' => $discountDetails['name'],
                'discount_description' => $discountDetails['description'],
                'discount_type' => $discountDetails['discount_type'],
                'discount_value' => $discountDetails['discount_value'],
                'was_active' => $discountDetails['is_active'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('Discount deleted');

        return response()->json([
            'success' => true,
            'message' => 'Discount deleted successfully.'
        ]);
    }

    /**
     * Toggle discount active status
     */
    public function toggle(Discount $discount)
    {
        $previousStatus = $discount->is_active;
        $discount->update(['is_active' => !$discount->is_active]);

        // Log the activity
        activity('financial_management')
            ->causedBy(auth()->user())
            ->performedOn($discount)
            ->withProperties([
                'action' => 'toggled_status',
                'discount_id' => $discount->id,
                'discount_name' => $discount->name,
                'previous_status' => $previousStatus,
                'new_status' => $discount->is_active,
                'status_change' => $previousStatus ? 'deactivated' : 'activated',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->log('Discount status toggled');

        $status = $discount->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Discount {$status} successfully."
        ]);
    }
}
