<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\AboutSection;
use App\Models\MissionValuesSection;
use App\Models\MissionValuesItem;
use App\Models\SchoolAtGlanceSection;
use App\Models\SchoolAtGlanceItem;
use App\Models\AcademicProgramsSection;
use App\Models\AcademicProgramsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomepageController extends Controller
{
    /**
     * Display the Homepage Manager dashboard
     */
    public function index()
    {
        // Check if user has permission
        // You can add permission check here if needed
        // abort_unless(auth()->user()->can('manage_homepage'), 403);

        return view('user-admin.homepage.index');
    }

    /**
     * Show the form for editing the hero section
     */
    public function editHeroSection()
    {
        // Get first hero section regardless of active status, or create new one
        $hero = HeroSection::orderBy('order')->first() ?? new HeroSection();
        return view('user-admin.homepage.edit-hero', compact('hero'));
    }

    /**
     * Update the hero section
     */
    public function updateHeroSection(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'background_type' => 'required|in:video,image,none',
            'background_video' => 'nullable|file|mimes:mp4,webm|max:51200', // 50MB
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB
        ]);

        // Get first hero section regardless of active status, or create new one
        $hero = HeroSection::orderBy('order')->first() ?? new HeroSection();

        $hero->title = $request->title;
        $hero->subtitle = $request->subtitle;
        $hero->background_type = $request->background_type;
        // Properly handle checkbox - it's only present in request when checked
        $hero->is_active = $request->has('is_active') ? true : false;

        // Handle video upload with corruption prevention
        if ($request->hasFile('background_video')) {
            $videoFile = $request->file('background_video');
            
            // Validate file is not corrupted
            if ($videoFile->isValid()) {
                try {
                    // Delete old video if exists
                    if ($hero->background_video_path && Storage::disk('public')->exists($hero->background_video_path)) {
                        Storage::disk('public')->delete($hero->background_video_path);
                    }
                    
                    // Generate unique filename to avoid conflicts
                    $filename = time() . '_' . uniqid() . '.' . $videoFile->getClientOriginalExtension();
                    $videoPath = $videoFile->storeAs('hero/videos', $filename, 'public');
                    
                    // Verify the file was actually stored and is not empty
                    $fullPath = storage_path('app/public/' . $videoPath);
                    if (file_exists($fullPath) && filesize($fullPath) > 1000) {
                        $hero->background_video_path = $videoPath;
                    } else {
                        // File is corrupted or too small, delete it
                        Storage::disk('public')->delete($videoPath);
                        return redirect()->back()->withErrors(['background_video' => 'Video file upload failed or file is corrupted. Please try again.'])->withInput();
                    }
                } catch (\Exception $e) {
                    \Log::error('Video upload failed: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['background_video' => 'Video upload failed: ' . $e->getMessage()])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['background_video' => 'Invalid video file. The file may be corrupted.'])->withInput();
            }
        }

        // Handle image upload with corruption prevention
        if ($request->hasFile('background_image')) {
            $imageFile = $request->file('background_image');
            
            // Validate file is not corrupted
            if ($imageFile->isValid()) {
                try {
                    // Delete old image if exists
                    if ($hero->background_image_path && Storage::disk('public')->exists($hero->background_image_path)) {
                        Storage::disk('public')->delete($hero->background_image_path);
                    }
                    
                    // Generate unique filename to avoid conflicts
                    $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                    $imagePath = $imageFile->storeAs('hero/images', $filename, 'public');
                    
                    // Verify the file was actually stored and is not empty
                    $fullPath = storage_path('app/public/' . $imagePath);
                    if (file_exists($fullPath) && filesize($fullPath) > 1000) {
                        $hero->background_image_path = $imagePath;
                    } else {
                        // File is corrupted or too small, delete it
                        Storage::disk('public')->delete($imagePath);
                        return redirect()->back()->withErrors(['background_image' => 'Image file upload failed or file is corrupted. Please try again.'])->withInput();
                    }
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['background_image' => 'Image upload failed: ' . $e->getMessage()])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['background_image' => 'Invalid image file. The file may be corrupted.'])->withInput();
            }
        }

        $hero->save();

        return redirect()->route('admin.homepage.hero.edit')->with('success', 'Hero section updated successfully!');
    }

    /**
     * Show the form for editing the about section
     */
    public function editAboutSection()
    {
        $about = AboutSection::orderBy('order')->first() ?? new AboutSection();
        return view('user-admin.homepage.edit-about', compact('about'));
    }

    /**
     * Update the about section
     */
    public function updateAboutSection(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB
        ]);

        $about = AboutSection::orderBy('order')->first() ?? new AboutSection();

        $about->heading = $request->heading;
        $about->description = $request->description;
        $about->is_active = $request->has('is_active') ? true : false;

        // Handle image upload with corruption prevention
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            
            if ($imageFile->isValid()) {
                try {
                    // Delete old image if exists
                    if ($about->image_path && Storage::disk('public')->exists($about->image_path)) {
                        Storage::disk('public')->delete($about->image_path);
                    }
                    
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                    $imagePath = $imageFile->storeAs('about/images', $filename, 'public');
                    
                    // Verify file was stored correctly
                    $fullPath = storage_path('app/public/' . $imagePath);
                    if (file_exists($fullPath) && filesize($fullPath) > 1000) {
                        $about->image_path = $imagePath;
                    } else {
                        Storage::disk('public')->delete($imagePath);
                        return redirect()->back()->withErrors(['image' => 'Image upload failed or file is corrupted. Please try again.'])->withInput();
                    }
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['image' => 'Invalid image file. The file may be corrupted.'])->withInput();
            }
        }

        $about->save();

        return redirect()->route('admin.homepage.about.edit')->with('success', 'About section updated successfully!');
    }

    /**
     * Show the form for editing the Mission & Values section
     */
    public function editMissionValuesSection()
    {
        $section = MissionValuesSection::with('items')->orderBy('order')->first();
        
        if (!$section) {
            // Create a default section if none exists
            $section = MissionValuesSection::create([
                'heading' => 'Our Mission & Values',
                'description' => 'Guiding every student to become a compassionate, innovative, and responsible leader for the future.',
                'is_active' => true,
                'order' => 1,
            ]);
        }
        
        return view('user-admin.homepage.edit-mission-values', compact('section'));
    }

    /**
     * Update the Mission & Values section
     */
    public function updateMissionValuesSection(Request $request)
    {
        $section = MissionValuesSection::orderBy('order')->first();
        
        if (!$section) {
            $section = new MissionValuesSection();
            $section->order = 1;
        }

        // Validate the request
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            // Validate existing items
            'items.*.id' => 'nullable|exists:mission_values_items,id',
            'items.*.title' => 'required|string|max:255',
            'items.*.description' => 'required|string',
            'items.*.icon' => 'required|string|max:255',
            'items.*.color' => 'required|string|max:7',
            'items.*.order' => 'required|integer|min:1',
        ]);

        // Update section
        $section->heading = $validated['heading'];
        $section->description = $validated['description'];
        $section->is_active = $request->has('is_active');
        $section->save();

        // Update or create items
        if (isset($validated['items'])) {
            $submittedItemIds = [];
            
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id']) && $itemData['id']) {
                    // Update existing item
                    $item = MissionValuesItem::find($itemData['id']);
                    if ($item) {
                        $item->update([
                            'title' => $itemData['title'],
                            'description' => $itemData['description'],
                            'icon' => $itemData['icon'],
                            'color' => $itemData['color'],
                            'order' => $itemData['order'],
                        ]);
                        $submittedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = $section->items()->create([
                        'title' => $itemData['title'],
                        'description' => $itemData['description'],
                        'icon' => $itemData['icon'],
                        'color' => $itemData['color'],
                        'order' => $itemData['order'],
                    ]);
                    $submittedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that were not submitted (removed by user)
            MissionValuesItem::where('mission_values_section_id', $section->id)
                ->whereNotIn('id', $submittedItemIds)
                ->delete();
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Mission & Values section updated successfully!');
    }

    /**
     * Delete a mission value item
     */
    public function deleteMissionValueItem($id)
    {
        $item = MissionValuesItem::find($id);
        
        if ($item) {
            $item->delete();
            return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    /**
     * Show the form for editing the School at a Glance section
     */
    public function editSchoolAtGlanceSection()
    {
        $section = SchoolAtGlanceSection::with('items')->orderBy('order')->first();
        
        if (!$section) {
            // Create a default section if none exists
            $section = SchoolAtGlanceSection::create([
                'heading' => 'School at a Glance',
                'description' => 'A quick look at what makes our school unique and outstanding.',
                'is_active' => true,
                'order' => 1,
            ]);
        }
        
        return view('user-admin.homepage.edit-school-at-glance', compact('section'));
    }

    /**
     * Update the School at a Glance section
     */
    public function updateSchoolAtGlanceSection(Request $request)
    {
        $section = SchoolAtGlanceSection::orderBy('order')->first();
        
        if (!$section) {
            $section = new SchoolAtGlanceSection();
            $section->order = 1;
        }

        // Validate the request
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            // Validate existing items
            'items.*.id' => 'nullable|exists:school_at_glance_items,id',
            'items.*.value' => 'required|string|max:255',
            'items.*.label' => 'required|string|max:255',
            'items.*.bg_color' => 'required|string|max:7',
            'items.*.text_color' => 'required|string|max:7',
            'items.*.order' => 'required|integer|min:1',
        ]);

        // Update section
        $section->heading = $validated['heading'];
        $section->description = $validated['description'];
        $section->is_active = $request->has('is_active');
        $section->save();

        // Update or create items
        if (isset($validated['items'])) {
            $submittedItemIds = [];
            
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id']) && $itemData['id']) {
                    // Update existing item
                    $item = SchoolAtGlanceItem::find($itemData['id']);
                    if ($item) {
                        $item->update([
                            'value' => $itemData['value'],
                            'label' => $itemData['label'],
                            'bg_color' => $itemData['bg_color'],
                            'text_color' => $itemData['text_color'],
                            'order' => $itemData['order'],
                        ]);
                        $submittedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = $section->items()->create([
                        'value' => $itemData['value'],
                        'label' => $itemData['label'],
                        'bg_color' => $itemData['bg_color'],
                        'text_color' => $itemData['text_color'],
                        'order' => $itemData['order'],
                    ]);
                    $submittedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that were not submitted (removed by user)
            SchoolAtGlanceItem::where('school_at_glance_section_id', $section->id)
                ->whereNotIn('id', $submittedItemIds)
                ->delete();
        }

        return redirect()->route('admin.homepage.index')->with('success', 'School at a Glance section updated successfully!');
    }

    /**
     * Show the form for editing the Academic Programs section
     */
    public function editAcademicProgramsSection()
    {
        $section = AcademicProgramsSection::with('items')->orderBy('order')->first();
        
        if (!$section) {
            // Create a default section if none exists
            $section = AcademicProgramsSection::create([
                'heading' => 'Academic Programs',
                'description' => 'Discover our comprehensive academic programs designed to prepare students for success',
                'is_active' => true,
                'order' => 1,
            ]);
        }
        
        return view('user-admin.homepage.edit-academic-programs', compact('section'));
    }

    /**
     * Update the Academic Programs section
     */
    public function updateAcademicProgramsSection(Request $request)
    {
        $section = AcademicProgramsSection::orderBy('order')->first();
        
        if (!$section) {
            $section = new AcademicProgramsSection();
            $section->order = 1;
        }

        // Validate the request
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
            // Validate existing items
            'items.*.id' => 'nullable|exists:academic_programs_items,id',
            'items.*.title' => 'required|string|max:255',
            'items.*.description' => 'required|string',
            'items.*.track_name' => 'nullable|string|max:255',
            'items.*.gradient_from' => 'required|string|max:7',
            'items.*.gradient_to' => 'required|string|max:7',
            'items.*.link_url' => 'nullable|url|max:255',
            'items.*.status' => 'required|in:active,coming_soon',
            'items.*.order' => 'required|integer|min:1',
        ]);

        // Update section
        $section->heading = $validated['heading'];
        $section->description = $validated['description'];
        $section->is_active = $request->has('is_active');
        $section->save();

        // Update or create items
        if (isset($validated['items'])) {
            $submittedItemIds = [];
            
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id']) && $itemData['id']) {
                    // Update existing item
                    $item = AcademicProgramsItem::find($itemData['id']);
                    if ($item) {
                        $item->update([
                            'title' => $itemData['title'],
                            'description' => $itemData['description'],
                            'track_name' => $itemData['track_name'] ?? null,
                            'gradient_from' => $itemData['gradient_from'],
                            'gradient_to' => $itemData['gradient_to'],
                            'link_url' => $itemData['link_url'] ?? null,
                            'status' => $itemData['status'],
                            'order' => $itemData['order'],
                        ]);
                        $submittedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = $section->items()->create([
                        'title' => $itemData['title'],
                        'description' => $itemData['description'],
                        'track_name' => $itemData['track_name'] ?? null,
                        'gradient_from' => $itemData['gradient_from'],
                        'gradient_to' => $itemData['gradient_to'],
                        'link_url' => $itemData['link_url'] ?? null,
                        'status' => $itemData['status'],
                        'order' => $itemData['order'],
                    ]);
                    $submittedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that were not submitted (removed by user)
            AcademicProgramsItem::where('academic_programs_section_id', $section->id)
                ->whereNotIn('id', $submittedItemIds)
                ->delete();
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Academic Programs section updated successfully!');
    }

    /**
     * Show the form for editing a specific section
     */
    public function editSection($section)
    {
        // Logic for editing specific sections will go here
        return view('user-admin.homepage.edit', compact('section'));
    }

    /**
     * Update a specific section
     */
    public function updateSection(Request $request, $section)
    {
        // Logic for updating sections will go here
        return redirect()->route('admin.homepage.index')->with('success', 'Section updated successfully!');
    }
}
