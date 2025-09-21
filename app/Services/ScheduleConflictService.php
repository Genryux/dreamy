<?php

namespace App\Services;

use App\Models\Section;
use App\Models\SectionSubject;
use Illuminate\Support\Collection;

class ScheduleConflictService
{
    /**
     * Check for schedule conflicts and return detailed results
     */
    public function checkConflicts(Section $section, array $scheduleData, ?int $excludeSectionSubjectId = null): array
    {
        $conflicts = [];
        
        // Only check conflicts if we have time data
        if (empty($scheduleData['start_time']) || empty($scheduleData['end_time'])) {
            return [
                'has_conflicts' => false,
                'conflicts' => [],
                'suggestions' => []
            ];
        }

        // 1. Section-level conflicts (same section, overlapping times)
        $sectionConflicts = $this->checkSectionConflicts($section, $scheduleData, $excludeSectionSubjectId);
        $conflicts = array_merge($conflicts, $sectionConflicts);

        // 2. Room-level conflicts (same room, overlapping times)
        if (!empty($scheduleData['room'])) {
            $roomConflicts = $this->checkRoomConflicts($scheduleData);
            $conflicts = array_merge($conflicts, $roomConflicts);
        }

        // 3. Teacher-level conflicts (same teacher, overlapping times)
        if (!empty($scheduleData['teacher_id'])) {
            $teacherConflicts = $this->checkTeacherConflicts($scheduleData, $excludeSectionSubjectId);
            $conflicts = array_merge($conflicts, $teacherConflicts);
        }

        // Generate suggestions if conflicts exist
        $suggestionsData = [];
        if (!empty($conflicts)) {
            $suggestionsData = $this->generateScheduleSuggestions($section, $scheduleData);
        }

        return [
            'has_conflicts' => !empty($conflicts),
            'conflicts' => $conflicts,
            'suggestions' => $suggestionsData['suggestions'] ?? [],
            'no_available_days' => $suggestionsData['no_available_days'] ?? [],
            'no_available_message' => $suggestionsData['no_available_message'] ?? null
        ];
    }

    /**
     * Check for section-level conflicts
     */
    private function checkSectionConflicts(Section $section, array $scheduleData, ?int $excludeId = null): array
    {
        $conflicts = [];
        
        $query = $section->sectionSubjects()
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->with('subject');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingSchedules = $query->get();

        foreach ($existingSchedules as $existing) {
            $conflictResult = $this->hasTimeConflict($scheduleData, $existing);
            if ($conflictResult['has_conflict']) {
                $conflictingDays = implode(', ', $conflictResult['conflicting_days']);
                $conflicts[] = [
                    'type' => 'section_conflict',
                    'message' => "Section {$section->name} already has {$existing->subject->name} scheduled on {$conflictingDays} at " . 
                                $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time),
                    'subject' => $existing->subject->name,
                    'section' => $section->name,
                    'time' => $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time),
                    'conflicting_days' => $conflictResult['conflicting_days']
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Check for room-level conflicts
     */
    private function checkRoomConflicts(array $scheduleData): array
    {
        $conflicts = [];
        
        $existingSchedules = SectionSubject::where('room', $scheduleData['room'])
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->with(['subject', 'section'])
            ->get();

        foreach ($existingSchedules as $existing) {
            $conflictResult = $this->hasTimeConflict($scheduleData, $existing);
            if ($conflictResult['has_conflict']) {
                $conflictingDays = implode(', ', $conflictResult['conflicting_days']);
                $conflicts[] = [
                    'type' => 'room_conflict',
                    'message' => "Room {$scheduleData['room']} is already booked for {$existing->subject->name} in {$existing->section->name} on {$conflictingDays} (" . 
                                $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time) . ")",
                    'subject' => $existing->subject->name,
                    'section' => $existing->section->name,
                    'room' => $scheduleData['room'],
                    'time' => $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time),
                    'conflicting_days' => $conflictResult['conflicting_days']
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Check for teacher-level conflicts
     */
    private function checkTeacherConflicts(array $scheduleData, ?int $excludeId = null): array
    {
        $conflicts = [];
        
        $query = SectionSubject::where('teacher_id', $scheduleData['teacher_id'])
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->with(['subject', 'section']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingSchedules = $query->get();

        foreach ($existingSchedules as $existing) {
            $conflictResult = $this->hasTimeConflict($scheduleData, $existing);
            if ($conflictResult['has_conflict']) {
                $conflictingDays = implode(', ', $conflictResult['conflicting_days']);
                $conflicts[] = [
                    'type' => 'teacher_conflict',
                    'message' => "Teacher is already assigned to {$existing->subject->name} in {$existing->section->name} on {$conflictingDays} (" . 
                                $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time) . ")",
                    'subject' => $existing->subject->name,
                    'section' => $existing->section->name,
                    'time' => $this->formatTime($existing->start_time) . " - " . $this->formatTime($existing->end_time),
                    'conflicting_days' => $conflictResult['conflicting_days']
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Check if two schedules have time conflicts and return conflicting days
     */
    private function hasTimeConflict(array $newSchedule, $existingSchedule): array
    {
        // Check if days overlap
        $newDays = $newSchedule['days_of_week'] ?? [];
        $existingDays = $existingSchedule->days_of_week ?? [];
        
        if (empty($newDays) || empty($existingDays)) {
            return ['has_conflict' => false, 'conflicting_days' => []];
        }

        $overlappingDays = array_intersect($newDays, $existingDays);
        
        if (empty($overlappingDays)) {
            return ['has_conflict' => false, 'conflicting_days' => []];
        }

        // Check if times overlap
        $newStart = strtotime($newSchedule['start_time']);
        $newEnd = strtotime($newSchedule['end_time']);
        $existingStart = strtotime($existingSchedule->start_time);
        $existingEnd = strtotime($existingSchedule->end_time);

        $timeOverlaps = ($newStart < $existingEnd && $newEnd > $existingStart);

        return [
            'has_conflict' => $timeOverlaps,
            'conflicting_days' => $timeOverlaps ? $overlappingDays : []
        ];
    }

    /**
     * Generate schedule suggestions when conflicts exist
     */
    private function generateScheduleSuggestions(Section $section, array $requestedSchedule): array
    {
        $suggestions = [];
        $noAvailableDays = [];
        $requestedDays = $requestedSchedule['days_of_week'] ?? [];
        $requestedDuration = 60; // Default 1 hour duration
        
        if (!empty($requestedSchedule['start_time']) && !empty($requestedSchedule['end_time'])) {
            $requestedDuration = (strtotime($requestedSchedule['end_time']) - strtotime($requestedSchedule['start_time'])) / 60;
        }

        // Available time slots (8 AM to 5 PM)
        $timeSlots = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
        ];

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // If no specific days requested, suggest all days
        if (empty($requestedDays)) {
            $requestedDays = $daysOfWeek;
        }

        // Generate suggestions for each day separately
        foreach ($requestedDays as $day) {
            $daySuggestions = [];
            
            foreach ($timeSlots as $startTime) {
                $endTime = date('H:i', strtotime($startTime . ' +' . $requestedDuration . ' minutes'));
                
                // Skip if end time goes beyond 5 PM
                if (strtotime($endTime) > strtotime('17:00')) {
                    continue;
                }

                $testSchedule = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'days_of_week' => [$day],
                    'room' => $requestedSchedule['room'] ?? '',
                    'teacher_id' => $requestedSchedule['teacher_id'] ?? ''
                ];

                // Check if this time slot is available
                if ($this->isTimeSlotAvailable($section, $testSchedule)) {
                    $daySuggestions[] = [
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'display' => "{$day} " . $this->formatTime($startTime) . " - " . $this->formatTime($endTime)
                    ];
                }
            }
            
            // Check if this day has any available times
            if (empty($daySuggestions)) {
                $noAvailableDays[] = $day;
            } else {
                // Add all available times for this day (limit to 8 per day to avoid UI overflow)
                $suggestions = array_merge($suggestions, array_slice($daySuggestions, 0, 8));
            }
        }

        // Return suggestions with information about days with no available times
        return [
            'suggestions' => $suggestions,
            'no_available_days' => $noAvailableDays,
            'no_available_message' => !empty($noAvailableDays) ? 
                "No available time slots found for: " . implode(', ', $noAvailableDays) : null
        ];
    }

    /**
     * Check if a time slot is available
     */
    private function isTimeSlotAvailable(Section $section, array $testSchedule): bool
    {
        // Check section-level conflicts
        $sectionConflicts = $section->sectionSubjects()
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get();

        foreach ($sectionConflicts as $existing) {
            $conflictResult = $this->hasTimeConflict($testSchedule, $existing);
            if ($conflictResult['has_conflict']) {
                return false;
            }
        }

        // Check room-level conflicts (if room is specified)
        if (!empty($testSchedule['room'])) {
            $roomConflicts = SectionSubject::where('room', $testSchedule['room'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();

            foreach ($roomConflicts as $existing) {
                $conflictResult = $this->hasTimeConflict($testSchedule, $existing);
                if ($conflictResult['has_conflict']) {
                    return false;
                }
            }
        }

        // Check teacher-level conflicts (if teacher is specified)
        if (!empty($testSchedule['teacher_id'])) {
            $teacherConflicts = SectionSubject::where('teacher_id', $testSchedule['teacher_id'])
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();

            foreach ($teacherConflicts as $existing) {
                $conflictResult = $this->hasTimeConflict($testSchedule, $existing);
                if ($conflictResult['has_conflict']) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Format time for display
     */
    private function formatTime($time): string
    {
        return date('g:i A', strtotime($time));
    }

    /**
     * Validate schedule data
     */
    public function validateScheduleData(array $data): array
    {
        $errors = [];

        // Validate time format
        if (!empty($data['start_time']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['start_time'])) {
            $errors[] = 'Invalid start time format';
        }

        if (!empty($data['end_time']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['end_time'])) {
            $errors[] = 'Invalid end time format';
        }

        // Validate time order
        if (!empty($data['start_time']) && !empty($data['end_time'])) {
            if (strtotime($data['start_time']) >= strtotime($data['end_time'])) {
                $errors[] = 'Start time must be before end time';
            }
        }

        // Validate days of week
        if (!empty($data['days_of_week'])) {
            $validDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($data['days_of_week'] as $day) {
                if (!in_array($day, $validDays)) {
                    $errors[] = "Invalid day: {$day}";
                }
            }
        }

        return $errors;
    }
}