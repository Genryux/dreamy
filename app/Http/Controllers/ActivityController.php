<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('activity.index', compact('activities'));
    }

    /**
     * Get activity logs for AJAX requests (for the school settings page)
     */
    public function getActivityLogs(Request $request)
    {
        $limit = $request->get('limit', 20);
        $logName = $request->get('log_name');
        
        $query = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');
            
        if ($logName) {
            $query->where('log_name', $logName);
        }
        
        $activities = $query->limit($limit)->get();
        
        return response()->json([
            'success' => true,
            'activities' => $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'log_name' => $activity->log_name,
                    'causer_name' => $activity->causer ? $activity->causer->first_name . ' ' . $activity->causer->last_name : 'System',
                    'causer_email' => $activity->causer ? $activity->causer->email : null,
                    'subject_type' => $activity->subject_type,
                    'subject_id' => $activity->subject_id,
                    'properties' => $activity->properties,
                    'created_at' => $activity->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $activity->created_at->diffForHumans(),
                ];
            })
        ]);
    }
}
