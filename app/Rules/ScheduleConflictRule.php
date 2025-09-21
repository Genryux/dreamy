<?php

namespace App\Rules;

use App\Services\ScheduleConflictService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ScheduleConflictRule implements ValidationRule
{
    protected $section;
    protected $excludeId;

    public function __construct($section, $excludeId = null)
    {
        $this->section = $section;
        $this->excludeId = $excludeId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $scheduleConflictService = app(ScheduleConflictService::class);
        
        // Get all the schedule data from the request
        $scheduleData = request()->only([
            'teacher_id', 'room', 'days_of_week', 'start_time', 'end_time'
        ]);

        // Check for conflicts
        $result = $scheduleConflictService->checkConflicts($this->section, $scheduleData, $this->excludeId);
        
        if ($result['has_conflicts']) {
            $conflictMessages = array_column($result['conflicts'], 'message');
            $fail('Schedule conflicts detected: ' . implode('; ', $conflictMessages));
        }
    }
}
