@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">School Settings</h1>

    @if (session('success'))
        @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @endif

    @if ($errors->any())
        @include('components.alert', ['type' => 'error', 'message' => 'Please fix the errors below.'])
    @endif

    <form method="POST" action="{{ route('admin.settings.school.update') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">School Name</label>
                <input type="text" name="name" value="{{ old('name', $setting->name) }}" class="w-full border rounded p-2" required>
                @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Short Name</label>
                <input type="text" name="short_name" value="{{ old('short_name', $setting->short_name) }}" class="w-full border rounded p-2">
                @error('short_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Address Line 1</label>
                <input type="text" name="address_line1" value="{{ old('address_line1', $setting->address_line1) }}" class="w-full border rounded p-2">
                @error('address_line1')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Address Line 2</label>
                <input type="text" name="address_line2" value="{{ old('address_line2', $setting->address_line2) }}" class="w-full border rounded p-2">
                @error('address_line2')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">City</label>
                <input type="text" name="city" value="{{ old('city', $setting->city) }}" class="w-full border rounded p-2">
                @error('city')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Province</label>
                <input type="text" name="province" value="{{ old('province', $setting->province) }}" class="w-full border rounded p-2">
                @error('province')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Country</label>
                <input type="text" name="country" value="{{ old('country', $setting->country) }}" class="w-full border rounded p-2">
                @error('country')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">ZIP</label>
                <input type="text" name="zip" value="{{ old('zip', $setting->zip) }}" class="w-full border rounded p-2">
                @error('zip')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" class="w-full border rounded p-2">
                @error('phone')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full border rounded p-2">
                @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Website</label>
                <input type="url" name="website" value="{{ old('website', $setting->website) }}" class="w-full border rounded p-2">
                @error('website')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Registrar Name</label>
                <input type="text" name="registrar_name" value="{{ old('registrar_name', $setting->registrar_name) }}" class="w-full border rounded p-2">
                @error('registrar_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Registrar Title</label>
                <input type="text" name="registrar_title" value="{{ old('registrar_title', $setting->registrar_title) }}" class="w-full border rounded p-2">
                @error('registrar_title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
@endsection


