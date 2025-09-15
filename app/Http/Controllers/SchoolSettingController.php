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
            $setting = new SchoolSetting();
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
        ]);

        $setting = SchoolSetting::query()->first();
        if (! $setting) {
            $setting = new SchoolSetting();
        }

        $setting->fill($validated);
        $setting->save();

        return redirect()->back()->with('success', 'School settings updated.');
    }
}


