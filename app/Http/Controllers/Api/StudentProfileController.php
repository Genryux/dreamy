<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentRecord;
use App\Services\StudentDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentProfileController extends Controller
{
    /**
     * Update student personal information
     */
    public function updatePersonalInfo(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'age' => 'nullable|integer|min:1|max:150',
            'gender' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:100',
            'middle_name' => 'nullable|string|max:50',
            'birthdate' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:100',
            'current_address' => 'nullable|string|max:500',
            'permanent_address' => 'nullable|string|max:500',
            'father_name' => 'nullable|string|max:100',
            'father_contact_number' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:100',
            'mother_contact_number' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:100',
            'guardian_contact_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $student = $user->student;
            $studentRecord = $student->record;
            
            // Update User table (for authentication)
            $userUpdates = [];
            if ($request->has('email_address') && $request->email_address !== null) {
                $userUpdates['email'] = $request->email_address;
            }
            if ($request->has('first_name') && $request->first_name !== null) {
                $userUpdates['first_name'] = $request->first_name;
            }
            if ($request->has('last_name') && $request->last_name !== null) {
                $userUpdates['last_name'] = $request->last_name;
            }
            
            if (!empty($userUpdates)) {
                $user->update($userUpdates);
            }
            
            // Update StudentRecord table (detailed personal data)
            if ($studentRecord) {
                $recordUpdates = [];
                
                // Personal details
                if ($request->has('age') && $request->age !== null) {
                    $recordUpdates['age'] = $request->age;
                }
                if ($request->has('gender') && $request->gender !== null) {
                    $recordUpdates['gender'] = $request->gender;
                }
                if ($request->has('contact_number') && $request->contact_number !== null) {
                    $recordUpdates['contact_number'] = $request->contact_number;
                }
                if ($request->has('middle_name') && $request->middle_name !== null) {
                    $recordUpdates['middle_name'] = $request->middle_name;
                }
                if ($request->has('birthdate') && $request->birthdate !== null) {
                    $recordUpdates['birthdate'] = $request->birthdate;
                }
                if ($request->has('place_of_birth') && $request->place_of_birth !== null) {
                    $recordUpdates['place_of_birth'] = $request->place_of_birth;
                }
                if ($request->has('current_address') && $request->current_address !== null) {
                    $recordUpdates['current_address'] = $request->current_address;
                }
                if ($request->has('permanent_address') && $request->permanent_address !== null) {
                    $recordUpdates['permanent_address'] = $request->permanent_address;
                }
                
                // Family information
                if ($request->has('father_name') && $request->father_name !== null) {
                    $recordUpdates['father_name'] = $request->father_name;
                }
                if ($request->has('father_contact_number') && $request->father_contact_number !== null) {
                    $recordUpdates['father_contact_number'] = $request->father_contact_number;
                }
                if ($request->has('mother_name') && $request->mother_name !== null) {
                    $recordUpdates['mother_name'] = $request->mother_name;
                }
                if ($request->has('mother_contact_number') && $request->mother_contact_number !== null) {
                    $recordUpdates['mother_contact_number'] = $request->mother_contact_number;
                }
                if ($request->has('guardian_name') && $request->guardian_name !== null) {
                    $recordUpdates['guardian_name'] = $request->guardian_name;
                }
                if ($request->has('guardian_contact_number') && $request->guardian_contact_number !== null) {
                    $recordUpdates['guardian_contact_number'] = $request->guardian_contact_number;
                }
                
                if (!empty($recordUpdates)) {
                    $studentRecord->update($recordUpdates);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Personal information updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update personal information',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
