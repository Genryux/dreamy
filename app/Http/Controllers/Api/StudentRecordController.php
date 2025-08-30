<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $studentRecords = StudentRecord::with(['student'])->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $studentRecords
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:10',
            'gender' => 'nullable|string|in:male,female',
            'birthdate' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'place_of_birth' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable|string|max:100',
            'belongs_to_ip' => 'nullable|boolean',
            'is_4ps_beneficiary' => 'nullable|boolean',
            'contact_number' => 'nullable|string|max:20',
            'cur_house_no' => 'nullable|string|max:50',
            'cur_street' => 'nullable|string|max:255',
            'cur_barangay' => 'nullable|string|max:255',
            'cur_city' => 'nullable|string|max:255',
            'cur_province' => 'nullable|string|max:255',
            'cur_country' => 'nullable|string|max:255',
            'cur_zip_code' => 'nullable|string|max:20',
            'perm_house_no' => 'nullable|string|max:50',
            'perm_street' => 'nullable|string|max:255',
            'perm_barangay' => 'nullable|string|max:255',
            'perm_city' => 'nullable|string|max:255',
            'perm_province' => 'nullable|string|max:255',
            'perm_country' => 'nullable|string|max:255',
            'perm_zip_code' => 'nullable|string|max:20',
            'father_last_name' => 'nullable|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_contact_number' => 'nullable|string|max:20',
            'mother_last_name' => 'nullable|string|max:255',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_contact_number' => 'nullable|string|max:20',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_contact_number' => 'nullable|string|max:20',
            'has_special_needs' => 'nullable|boolean',
            'special_needs' => 'nullable|string',
            'last_grade_level_completed' => 'nullable|string|max:50',
            'last_school_attended' => 'nullable|string|max:255',
            'last_school_year_completed' => 'nullable|string|max:20',
            'school_id' => 'nullable|string|max:50'
        ]);

        $studentRecord = StudentRecord::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student record created successfully',
            'data' => $studentRecord->load('student')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentRecord $studentRecord): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $studentRecord->load('student')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentRecord $studentRecord): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:10',
            'gender' => 'nullable|string|in:male,female',
            'birthdate' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'place_of_birth' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable|string|max:100',
            'belongs_to_ip' => 'nullable|boolean',
            'is_4ps_beneficiary' => 'nullable|boolean',
            'contact_number' => 'nullable|string|max:20',
            'cur_house_no' => 'nullable|string|max:50',
            'cur_street' => 'nullable|string|max:255',
            'cur_barangay' => 'nullable|string|max:255',
            'cur_city' => 'nullable|string|max:255',
            'cur_province' => 'nullable|string|max:255',
            'cur_country' => 'nullable|string|max:255',
            'cur_zip_code' => 'nullable|string|max:20',
            'perm_house_no' => 'nullable|string|max:50',
            'perm_street' => 'nullable|string|max:255',
            'perm_barangay' => 'nullable|string|max:255',
            'perm_city' => 'nullable|string|max:255',
            'perm_province' => 'nullable|string|max:255',
            'perm_country' => 'nullable|string|max:255',
            'perm_zip_code' => 'nullable|string|max:20',
            'father_last_name' => 'nullable|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_contact_number' => 'nullable|string|max:20',
            'mother_last_name' => 'nullable|string|max:255',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_contact_number' => 'nullable|string|max:20',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_contact_number' => 'nullable|string|max:20',
            'has_special_needs' => 'nullable|boolean',
            'special_needs' => 'nullable|string',
            'last_grade_level_completed' => 'nullable|string|max:50',
            'last_school_attended' => 'nullable|string|max:255',
            'last_school_year_completed' => 'nullable|string|max:20',
            'school_id' => 'nullable|string|max:50'
        ]);

        $studentRecord->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student record updated successfully',
            'data' => $studentRecord->load('student')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRecord $studentRecord): JsonResponse
    {
        $studentRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student record deleted successfully'
        ]);
    }
} 