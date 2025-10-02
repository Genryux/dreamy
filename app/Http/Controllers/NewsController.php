<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Notifications\ImmediateNotification;
use App\Notifications\QueuedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user-admin.homepage.news.index');
    }

    /**
     * Get news data for DataTable
     */
    public function getNews(Request $request)
    {
        $query = News::query();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status_filter')) {
            $query->where('status', $status);
        }

        // Sorting
        $columns = ['id', 'title', 'status', 'published_at', 'created_at'];
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $sortColumn = $columns[$orderColumnIndex] ?? 'id';
        $query->orderBy($sortColumn, $orderDir);

        $total = $query->count();
        $filtered = $total;

        $start = $request->input('start', 0);
        $data = $query
            ->offset($start)
            ->limit($request->length)
            ->get()
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => \Str::limit($item->content, 50),
                    'status' => $item->status,
                    'visibility' => $item->visibility ?? 'both',
                    'is_announcement' => $item->is_announcement ?? false,
                    'type' => ($item->is_announcement ?? false) ? 'Announcement' : 'News',
                    'published_at' => $item->published_at ? $item->published_at->format('M d, Y') : '-',
                    'created_at' => $item->created_at->format('M d, Y'),
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return response()->json($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'visibility' => 'required|in:public,students_only,both',
            'is_announcement' => 'boolean'
        ]);

        try {
            $news = News::create([
                'title' => $request->title,
                'content' => $request->content,
                'status' => $request->status,
                'visibility' => $request->visibility,
                'is_announcement' => $request->boolean('is_announcement'),
                'published_at' => $request->status === 'published' ? now() : null
            ]);



            return response()->json([
                'success' => 'News created successfully',
                'data' => $news
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to create news: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'visibility' => 'required|in:public,students_only,both',
            'is_announcement' => 'boolean'
        ]);

        try {
            $news->update([
                'title' => $request->title,
                'content' => $request->content,
                'status' => $request->status,
                'visibility' => $request->visibility,
                'is_announcement' => $request->boolean('is_announcement'),
                'published_at' => $request->status === 'published' && !$news->published_at ? now() : $news->published_at
            ]);

            return response()->json([
                'success' => 'News updated successfully',
                'data' => $news
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to update news: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Handle both create and update in one method for easier debugging
     */
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'visibility' => 'required|in:public,students_only,both',
            'is_announcement' => 'boolean'
        ]);

        try {
            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'status' => $request->status,
                'visibility' => $request->visibility,
                'is_announcement' => $request->boolean('is_announcement'),
            ];

            if ($request->has('news_id') && $request->news_id) {
                // Update existing news
                $news = News::findOrFail($request->news_id);
                $data['published_at'] = $request->status === 'published' && !$news->published_at ? now() : $news->published_at;
                $news->update($data);
                $message = 'News updated successfully';
            } else {
                // Create new news
                $data['published_at'] = $request->status === 'published' ? now() : null;
                $news = News::create($data);
                $message = 'News created successfully';
            }

            $students = \App\Models\User::role(['student'])->get();
            $admins = \App\Models\User::role(['registrar', 'super_admin'])->get();

            if (!$admins->isEmpty()) {
                Notification::send($admins, new \App\Notifications\QueuedNotification(
                    "News & Announcement",
                    "A new announcement has been posted. Check your dashboard page for details.!",
                    url('/admin/users')
                ));

                Notification::route('broadcast', 'admins')
                    ->notify(new \App\Notifications\ImmediateNotification(
                        "News & Announcement",
                        "A new announcement has been posted. Check your dashboard page for details.!",
                        url('/admin/users')
                    ));
            }

            // if (!$students->isEmpty()) {
            //     // Generate a shared ID for both queued and immediate notifications
            //     $sharedNotificationId = 'test-student-' . time() . '-' . uniqid();

            //     // Database notification (queued)
            //     Notification::send($students, new QueuedNotification(
            //         "News & Announcement",
            //         "A new announcement has been posted. Check your dashboard page for details.!",
            //         null, // No URL needed for mobile
            //         $sharedNotificationId // Shared ID for mobile app matching
            //     ));

            //     // Real-time broadcast (immediate)
            //     Notification::route('broadcast', 'students')
            //         ->notify(new ImmediateNotification(
            //             "News & Announcement",
            //             "A new announcement has been posted. Check your dashboard page for details.!",
            //             null, // No URL needed for mobile
            //             $sharedNotificationId // Same shared ID for matching
            //         ));
            // }

            return response()->json([
                'success' => $message,
                'data' => $news
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to save news: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        try {
            $news->delete();
            return response()->json([
                'success' => 'News deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to delete news: ' . $th->getMessage()
            ], 500);
        }
    }
}
