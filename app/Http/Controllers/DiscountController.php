<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of discounts
     */
    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'desc')->get();
        return view('user-admin.discounts.index', compact('discounts'));
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
            return back()->withErrors(['discount_value' => 'Percentage discount cannot exceed 100%']);
        }

        // Handle is_active checkbox properly
        $validated['is_active'] = $request->has('is_active');

        Discount::create($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discount created successfully.');
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
            return back()->withErrors(['discount_value' => 'Percentage discount cannot exceed 100%']);
        }

        // Handle is_active checkbox properly
        $validated['is_active'] = $request->has('is_active');

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified discount
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Discount deleted successfully.');
    }

    /**
     * Toggle discount active status
     */
    public function toggle(Discount $discount)
    {
        $discount->update(['is_active' => !$discount->is_active]);

        $status = $discount->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.discounts.index')
            ->with('success', "Discount {$status} successfully.");
    }
}
