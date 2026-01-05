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
use App\Models\ReasonSection;
use App\Models\ReasonItem;
use App\Models\AlumniSection;
use App\Models\AlumniItem;
use App\Models\CampusTourSection;
use App\Models\CampusTourItem;
use App\Models\HowToApplySection;
use App\Models\HowToApplyStep;
use App\Models\HomepageNotice;
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

        // Get all section data for the homepage manager dashboard
        $heroSection = HeroSection::orderBy('order')->first();
        $aboutSection = AboutSection::orderBy('order')->first();
        $missionValuesSection = MissionValuesSection::with('items')->orderBy('order')->first();
        $glanceSection = SchoolAtGlanceSection::with('items')->orderBy('order')->first();
        $academicProgramsSection = AcademicProgramsSection::with('items')->orderBy('order')->first();
        $reasonSection = ReasonSection::with('items')->orderBy('order')->first();
        $alumniSection = AlumniSection::with('items')->orderBy('order')->first();
        $campusTour = CampusTourSection::with('items')->orderBy('order')->first();
        $howToApply = HowToApplySection::with('steps')->orderBy('order')->first();
        $homepageNotices = HomepageNotice::orderBy('order')->get();

        // Calculate analytics
        $totalSections = 10; // Fixed number of homepage sections
        
        // Count active sections
        $activeSections = 0;
        if ($heroSection && $heroSection->is_active) $activeSections++;
        if ($aboutSection && $aboutSection->is_active) $activeSections++;
        if ($missionValuesSection && $missionValuesSection->is_active) $activeSections++;
        if ($glanceSection && $glanceSection->is_active) $activeSections++;
        if ($academicProgramsSection && $academicProgramsSection->is_active) $activeSections++;
        if ($reasonSection && $reasonSection->is_active) $activeSections++;
        if ($alumniSection && $alumniSection->is_active) $activeSections++;
        if ($campusTour && $campusTour->is_active) $activeSections++;
        if ($howToApply && $howToApply->is_active) $activeSections++;
        if ($homepageNotices->where('is_active', true)->count() > 0) $activeSections++;

        // Count total images
        $totalImages = 0;
        if ($heroSection && $heroSection->background_image) $totalImages++;
        if ($aboutSection) {
            if ($aboutSection->image_left) $totalImages++;
            if ($aboutSection->image_right) $totalImages++;
        }
        if ($campusTour && $campusTour->items) {
            $totalImages += $campusTour->items->count();
        }
        if ($alumniSection && $alumniSection->items) {
            $totalImages += $alumniSection->items->whereNotNull('photo')->count();
        }

        // Get last updated time
        $allSections = collect([
            $heroSection,
            $aboutSection,
            $missionValuesSection,
            $glanceSection,
            $academicProgramsSection,
            $reasonSection,
            $alumniSection,
            $campusTour,
            $howToApply
        ])->filter()->sortByDesc('updated_at');
        
        $lastUpdated = $allSections->first() ? $allSections->first()->updated_at : null;

        return view('user-admin.homepage.index', compact(
            'heroSection',
            'aboutSection',
            'missionValuesSection',
            'glanceSection',
            'academicProgramsSection',
            'reasonSection',
            'alumniSection',
            'campusTour',
            'howToApply',
            'homepageNotices',
            'totalSections',
            'activeSections',
            'totalImages',
            'lastUpdated'
        ));
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
     * Show the form for editing the Reason (Why Choose) section
     */
    public function editReasonSection()
    {
        $section = ReasonSection::with('items')->orderBy('order')->first();
        
        if (!$section) {
            $section = ReasonSection::create([
                'heading' => 'Why Choose Dreamy School?',
                'description' => 'Discover what makes us the preferred choice for quality education',
                'is_active' => true,
                'order' => 1,
            ]);
        }

        return view('user-admin.homepage.edit-reason', compact('section'));
    }

    /**
     * Update the Reason (Why Choose) section
     */
    public function updateReasonSection(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'items' => 'array',
            'items.*.title' => 'required|string|max:255',
            'items.*.description' => 'required|string',
            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'items.*.order' => 'required|integer|min:1',
        ]);

        $section = ReasonSection::orderBy('order')->first();

        if (!$section) {
            $section = ReasonSection::create([
                'heading' => $request->heading,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'order' => 1,
            ]);
        } else {
            $section->update([
                'heading' => $request->heading,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);
        }

        // Process items
        if ($request->has('items')) {
            $submittedItemIds = [];

            foreach ($request->items as $index => $itemData) {
                $itemDataToSave = [
                    'title' => $itemData['title'],
                    'description' => $itemData['description'],
                    'order' => $itemData['order'],
                ];

                // Handle image upload
                if ($request->hasFile("items.{$index}.image")) {
                    $file = $request->file("items.{$index}.image");
                    if ($file->isValid()) {
                        $filename = 'reason_' . time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('homepage/reason', $filename, 'public');
                        
                        if ($path && Storage::disk('public')->exists($path)) {
                            $itemDataToSave['image'] = $path;
                        }
                    }
                }

                if (!empty($itemData['id'])) {
                    // Update existing item
                    $item = ReasonItem::find($itemData['id']);
                    if ($item) {
                        // Delete old image if new one uploaded
                        if (isset($itemDataToSave['image']) && $item->image) {
                            Storage::disk('public')->delete($item->image);
                        }
                        $item->update($itemDataToSave);
                        $submittedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = $section->items()->create($itemDataToSave);
                    $submittedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that were not submitted (removed by user)
            $itemsToDelete = ReasonItem::where('reason_section_id', $section->id)
                ->whereNotIn('id', $submittedItemIds)
                ->get();
            
            foreach ($itemsToDelete as $item) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $item->delete();
            }
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Why Choose section updated successfully!');
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
     * Show the form for editing the Alumni section
     */
    public function editAlumniSection()
    {
        $section = AlumniSection::with('items')->orderBy('order')->first();
        
        if (!$section) {
            $section = AlumniSection::create([
                'heading' => 'Alumni Success Stories',
                'description' => 'Meet some of our outstanding alumni and see where their Dreamy School journey has taken them.',
                'is_active' => true,
                'order' => 1,
            ]);
        }

        return view('user-admin.homepage.edit-alumni', compact('section'));
    }

    /**
     * Update the Alumni section
     */
    public function updateAlumniSection(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'items' => 'array',
            'items.*.name' => 'required|string|max:255',
            'items.*.class_year' => 'nullable|string|max:255',
            'items.*.track' => 'nullable|string|max:255',
            'items.*.quote' => 'required|string',
            'items.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'items.*.order' => 'required|integer|min:1',
        ]);

        $section = AlumniSection::orderBy('order')->first();

        $sectionData = [
            'heading' => $request->heading,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ];

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            if ($file->isValid()) {
                $filename = 'alumni_bg_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('homepage/alumni', $filename, 'public');
                
                if ($path && Storage::disk('public')->exists($path)) {
                    // Delete old image
                    if ($section && $section->background_image) {
                        Storage::disk('public')->delete($section->background_image);
                    }
                    $sectionData['background_image'] = $path;
                }
            }
        }

        if (!$section) {
            $sectionData['order'] = 1;
            $section = AlumniSection::create($sectionData);
        } else {
            $section->update($sectionData);
        }

        // Process items
        if ($request->has('items')) {
            $submittedItemIds = [];

            foreach ($request->items as $index => $itemData) {
                $itemDataToSave = [
                    'name' => $itemData['name'],
                    'class_year' => $itemData['class_year'] ?? null,
                    'track' => $itemData['track'] ?? null,
                    'quote' => $itemData['quote'],
                    'order' => $itemData['order'],
                ];

                // Handle photo upload
                if ($request->hasFile("items.{$index}.photo")) {
                    $file = $request->file("items.{$index}.photo");
                    if ($file->isValid()) {
                        $filename = 'alumni_' . time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('homepage/alumni', $filename, 'public');
                        
                        if ($path && Storage::disk('public')->exists($path)) {
                            $itemDataToSave['photo'] = $path;
                        }
                    }
                }

                if (!empty($itemData['id'])) {
                    // Update existing item
                    $item = AlumniItem::find($itemData['id']);
                    if ($item) {
                        // Delete old photo if new one uploaded
                        if (isset($itemDataToSave['photo']) && $item->photo) {
                            Storage::disk('public')->delete($item->photo);
                        }
                        $item->update($itemDataToSave);
                        $submittedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = $section->items()->create($itemDataToSave);
                    $submittedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that were not submitted (removed by user)
            $itemsToDelete = AlumniItem::where('alumni_section_id', $section->id)
                ->whereNotIn('id', $submittedItemIds)
                ->get();
            
            foreach ($itemsToDelete as $item) {
                if ($item->photo) {
                    Storage::disk('public')->delete($item->photo);
                }
                $item->delete();
            }
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Alumni section updated successfully!');
    }

    /**
     * Show the form for editing the Campus Tour section
     */
    public function editCampusTourSection()
    {
        $section = CampusTourSection::with('items')->orderBy('order')->first();
        
        if (!$section) {
            $section = CampusTourSection::create([
                'heading' => 'Virtual Campus Tour',
                'description' => 'Explore our modern campus and facilities from the comfort of your home.',
                'is_active' => true,
                'order' => 1,
            ]);
        }

        return view('user-admin.homepage.edit-campus-tour', compact('section'));
    }

    /**
     * Update the Campus Tour section
     */
    public function updateCampusTourSection(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'items' => 'array',
            'items.*.title' => 'required|string|max:255',
            'items.*.description' => 'required|string',
            'items.*.icon' => 'nullable|string|max:255',
            'items.*.highlight' => 'nullable|string|max:255',
            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'items.*.order' => 'required|integer|min:1',
        ]);

        $section = CampusTourSection::orderBy('order')->first();

        if (!$section) {
            $section = CampusTourSection::create([
                'heading' => $request->heading,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'order' => 1,
            ]);
        } else {
            $section->update([
                'heading' => $request->heading,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);
        }

        // Process items
        if ($request->has('items')) {
            $submittedItemIds = [];

            foreach ($request->items as $index => $itemData) {
                $itemDataToSave = [
                    'title' => $itemData['title'],
                    'description' => $itemData['description'],
                    'icon' => $itemData['icon'] ?? 'fi-rr-marker',
                    'highlight' => $itemData['highlight'] ?? null,
                    'order' => $itemData['order'],
                ];

                // Handle image upload
                if ($request->hasFile("items.{$index}.image")) {
                    $file = $request->file("items.{$index}.image");
                    if ($file->isValid()) {
                        $filename = 'campus_tour_' . time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('homepage/campus-tour', $filename, 'public');
                        
                        if ($path && Storage::disk('public')->exists($path)) {
                            $itemDataToSave['image'] = $path;
                        }
                    }
                }

                if (!empty($itemData['id'])) {
                    // Update existing item
                    $item = CampusTourItem::find($itemData['id']);
                    if ($item) {
                        // Delete old image if new one uploaded
                        if (isset($itemDataToSave['image']) && $item->image) {
                            Storage::disk('public')->delete($item->image);
                        }
                        $item->update($itemDataToSave);
                        $submittedItemIds[] = $item->id;
                    }
                } else {
                    // Create new item
                    $newItem = $section->items()->create($itemDataToSave);
                    $submittedItemIds[] = $newItem->id;
                }
            }
            
            // Delete items that were not submitted (removed by user)
            $itemsToDelete = CampusTourItem::where('campus_tour_section_id', $section->id)
                ->whereNotIn('id', $submittedItemIds)
                ->get();
            
            foreach ($itemsToDelete as $item) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $item->delete();
            }
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Campus Tour section updated successfully!');
    }

    /**
     * Show the form for editing the How to Apply section
     */
    public function editHowToApplySection()
    {
        $section = HowToApplySection::with('steps')->orderBy('order')->first();
        
        if (!$section) {
            $section = HowToApplySection::create([
                'heading' => 'How to Apply',
                'description' => 'Follow these simple steps to start your Dreamy School journey.',
                'button_text' => 'Apply Now',
                'button_link' => '/portal/register',
                'is_active' => true,
                'order' => 1,
            ]);
        }

        return view('user-admin.homepage.edit-how-to-apply', compact('section'));
    }

    /**
     * Update the How to Apply section
     */
    public function updateHowToApplySection(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'steps' => 'array',
            'steps.*.step_number' => 'required|integer|min:1',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.description' => 'required|string',
            'steps.*.icon' => 'nullable|string|max:255',
            'steps.*.order' => 'required|integer|min:1',
        ]);

        $section = HowToApplySection::orderBy('order')->first();

        if (!$section) {
            $section = HowToApplySection::create([
                'heading' => $request->heading,
                'description' => $request->description,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link,
                'is_active' => $request->has('is_active'),
                'order' => 1,
            ]);
        } else {
            $section->update([
                'heading' => $request->heading,
                'description' => $request->description,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link,
                'is_active' => $request->has('is_active'),
            ]);
        }

        // Process steps
        if ($request->has('steps')) {
            $submittedStepIds = [];

            foreach ($request->steps as $index => $stepData) {
                $stepDataToSave = [
                    'step_number' => $stepData['step_number'],
                    'title' => $stepData['title'],
                    'description' => $stepData['description'],
                    'icon' => $stepData['icon'] ?? null,
                    'order' => $stepData['order'],
                ];

                if (!empty($stepData['id'])) {
                    // Update existing step
                    $step = HowToApplyStep::find($stepData['id']);
                    if ($step) {
                        $step->update($stepDataToSave);
                        $submittedStepIds[] = $step->id;
                    }
                } else {
                    // Create new step
                    $newStep = $section->steps()->create($stepDataToSave);
                    $submittedStepIds[] = $newStep->id;
                }
            }
            
            // Delete steps that were not submitted (removed by user)
            HowToApplyStep::where('how_to_apply_section_id', $section->id)
                ->whereNotIn('id', $submittedStepIds)
                ->delete();
        }

        return redirect()->route('admin.homepage.index')->with('success', 'How to Apply section updated successfully!');
    }

    /**
     * Update a specific section
     */
    public function updateSection(Request $request, $section)
    {
        // Logic for updating sections will go here
        return redirect()->route('admin.homepage.index')->with('success', 'Section updated successfully!');
    }

    /**
     * Show the form for editing the notice section
     */
    public function editNotice()
    {
        $notices = HomepageNotice::orderBy('order')->get();
        return view('user-admin.homepage.edit-notice', compact('notices'));
    }

    /**
     * Update the notice section
     */
    public function updateNotice(Request $request)
    {
        $request->validate([
            'notices' => 'array',
            'notices.*.message' => 'required|string',
            'notices.*.bg_color' => 'required|string|max:7',
            'notices.*.text_color' => 'required|string|max:7',
            'notices.*.link_url' => 'nullable|url|max:255',
            'notices.*.starts_at' => 'nullable|date',
            'notices.*.ends_at' => 'nullable|date|after_or_equal:notices.*.starts_at',
            'notices.*.order' => 'required|integer|min:1',
        ]);

        if ($request->has('notices')) {
            $submittedIds = [];

            foreach ($request->notices as $noticeData) {
                $dataToSave = [
                    'message' => $noticeData['message'],
                    'bg_color' => $noticeData['bg_color'],
                    'text_color' => $noticeData['text_color'],
                    'link_url' => $noticeData['link_url'] ?? null,
                    'is_scrolling' => isset($noticeData['is_scrolling']),
                    'is_dismissible' => isset($noticeData['is_dismissible']),
                    'is_active' => isset($noticeData['is_active']),
                    'starts_at' => $noticeData['starts_at'] ?? null,
                    'ends_at' => $noticeData['ends_at'] ?? null,
                    'order' => $noticeData['order'],
                ];

                if (!empty($noticeData['id'])) {
                    $notice = HomepageNotice::find($noticeData['id']);
                    if ($notice) {
                        $notice->update($dataToSave);
                        $submittedIds[] = $notice->id;
                    }
                } else {
                    $newNotice = HomepageNotice::create($dataToSave);
                    $submittedIds[] = $newNotice->id;
                }
            }

            // Delete notices that were removed
            HomepageNotice::whereNotIn('id', $submittedIds)->delete();
        } else {
            // If no notices submitted, delete all
            HomepageNotice::truncate();
        }

        return redirect()->route('admin.homepage.index')->with('success', 'Homepage notices updated successfully!');
    }
}
