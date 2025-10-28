<?php

namespace App\Http\Controllers;

use App\Models\WebBackground;
use App\Models\News;
use App\Models\Program;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WebsiteResourceController extends Controller
{

    public function homepage()
    {
        $background = WebBackground::get('site_background');

        // In development, auto-clear corrupted files
        if (config('app.debug') && $background) {
            $fullPath = storage_path('app/public/' . $background);
            if (!file_exists($fullPath) || filesize($fullPath) < 1000) {
                WebBackground::set('site_background', null);
                $background = null;
            }
        }

        // Get latest published news for public (limit to 6 for homepage)
        $news = News::published()
            ->forPublic()
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        // Get programs and manually link them to appropriate tracks
        $programs = Program::with('track')
            ->orderBy('name')
            ->limit(6)
            ->get();

        // Don't force incorrect relationships - if programs don't have tracks, leave them empty
        // The view will handle displaying programs without tracks gracefully

        // Get all active tracks for reference
        $tracks = Track::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('home', compact('background', 'news', 'programs', 'tracks'));
    }

    public function UploadMainBg(Request $request)
    {
        $request->validate([
            'background' => [
                'required',
                'file',
                'max:51200', // 50MB
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    $allowedImages = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $allowedVideos = ['mp4', 'webm']; // Restrict to web-safe formats only

                    if (!in_array($ext, array_merge($allowedImages, $allowedVideos))) {
                        $fail('Only JPG, PNG, GIF, WebP, MP4, and WebM files are allowed.');
                    }

                    // Additional video validation
                    if (in_array($ext, $allowedVideos)) {
                        $mimeType = $value->getMimeType();
                        if (!in_array($mimeType, ['video/mp4', 'video/webm'])) {
                            $fail('Video must be a valid MP4 or WebM file.');
                        }
                    }
                },
            ],
        ]);

        try {
            $file = $request->file('background');
            $ext = strtolower($file->getClientOriginalExtension());

            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $ext;
            $path = $file->storeAs('backgrounds', $filename, 'public');

            // Verify the file was stored correctly
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('File was not stored correctly');
            }

            // For videos, do a basic validation
            if (in_array($ext, ['mp4', 'webm'])) {
                $fullPath = Storage::disk('public')->path($path);
                $fileSize = filesize($fullPath);

                if ($fileSize < 1000) { // Less than 1KB is suspicious
                    Storage::disk('public')->delete($path);
                    throw new \Exception('Video file appears to be corrupted');
                }
            }

            WebBackground::set('site_background', $path);

            return response()->json([
                'success' => 'File uploaded successfully',
                'path' => $path,
                'type' => in_array($ext, ['mp4', 'webm']) ? 'video' : 'image'
            ]);
        } catch (\Throwable $th) {
            Log::error('Background upload error: ' . $th->getMessage());
            return response()->json([
                'error' => 'Upload failed: ' . $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        return view('user-admin.homepage.landing-page.index');
    }

    /**
     * Display all published news for public viewing
     */
    public function news()
    {
        $news = News::published()
            ->forPublic()
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('public.news.index', compact('news'));
    }

    /**
     * Display a single news article for public viewing
     */
    public function showNews(News $news)
    {
        // Only show published news that's visible to public
        if ($news->status !== 'published' || !in_array($news->visibility, ['public', 'both'])) {
            abort(404);
        }

        // Get related news (other published articles visible to public)
        $relatedNews = News::published()
            ->forPublic()
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('public.news.show', compact('news', 'relatedNews'));
    }
}
