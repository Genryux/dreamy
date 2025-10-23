<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SchoolSettingController extends Controller
{
    public function edit()
    {
        $setting = SchoolSetting::query()->first();
        if (! $setting) {
            // Create a new setting with default values
            $setting = new SchoolSetting([
                'name' => 'Dreamy School Philippines',
                'short_name' => null,
                'address_line1' => null,
                'address_line2' => null,
                'city' => null,
                'province' => null,
                'country' => null,
                'zip' => null,
                'phone' => null,
                'email' => null,
                'website' => null,
                'down_payment' => null,
                'due_day_of_month' => 10, // Default to 10th of the month
                'use_last_day_if_shorter' => true,
                'logo_path' => null,
            ]);
        }

        return view('user-admin.settings.school', compact('setting'));
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'short_name' => ['nullable','string','max:255'],
            'address_line1' => ['nullable','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'province' => ['nullable','string','max:255'],
            'country' => ['nullable','string','max:255'],
            'zip' => ['nullable','string','max:32'],
            'phone' => ['nullable','string','max:64'],
            'email' => ['nullable','email','max:255'],
            'website' => ['nullable','url','max:255'],
            'registrar_name' => ['nullable','string','max:255'],
            'registrar_title' => ['nullable','string','max:255'],
            // Financial/Payments
            'down_payment' => ['nullable','numeric','min:0'],
            'due_day_of_month' => ['nullable','integer','min:1','max:31'],
            'use_last_day_if_shorter' => ['nullable','boolean'],
        ]);

        $setting = SchoolSetting::query()->first();
        if (! $setting) {
            $setting = new SchoolSetting();
        }

        // Normalize formatted currency input if provided
        if (isset($validated['down_payment'])) {
            // in case it arrives formatted, strip non-digits
            $validated['down_payment'] = is_string($validated['down_payment'])
                ? (int) preg_replace('/[^\d]/', '', $validated['down_payment'])
                : $validated['down_payment'];
        }

        $setting->fill($validated);
        $setting->save();

        // Log the activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($setting)
            ->withProperties([
                'changes' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('School settings updated');

        return redirect()->back()->with('success', 'School settings updated.');
    }

    /**
     * Update only payment-related settings via AJAX (JSON response).
     */
    public function updatePayments(Request $request)
    {

        $validated = $request->validate([
            'down_payment' => ['nullable','numeric','min:0','max:999999.99'],
            'due_day_of_month' => ['nullable','integer','min:1','max:31'],
        ]);

        try {
            $setting = SchoolSetting::query()->first();
            if (! $setting) {
                $setting = new SchoolSetting();
            }

            // Process down_payment value
            if (isset($validated['down_payment']) && $validated['down_payment'] !== null) {
                // Convert to integer if it's a string (remove formatting)
                $validated['down_payment'] = is_string($validated['down_payment'])
                    ? (int) preg_replace('/[^\d]/', '', $validated['down_payment'])
                    : (int) $validated['down_payment'];
            }


            $setting->fill($validated);
            $setting->save();

            // Log the activity
            activity('payment')
                ->causedBy(auth()->user())
                ->performedOn($setting)
                ->withProperties([
                    'changes' => $validated,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('Payment settings updated');

            // Audit logging for payment settings update
            \Log::info('Payment settings updated', [
                'down_payment' => $setting->down_payment,
                'due_day_of_month' => $setting->due_day_of_month,
                'updated_by' => auth()->user()->id,
                'updated_by_email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment settings updated successfully',
                'data' => [
                    'down_payment' => $setting->down_payment,
                    'due_day_of_month' => $setting->due_day_of_month,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Payment settings update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to update payment settings: ' . $e->getMessage(),
            ], 422);
        }
    }
}


