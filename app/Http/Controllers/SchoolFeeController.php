<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\SchoolFee;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SchoolFeeController extends Controller
{

    public function getSchoolFees(Request $request)
    {
        $query = SchoolFee::query();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->whereAny(['name', 'grade_level'], 'like', "%{$search}%");
        }

        if ($program = $request->input('program_filter')) {
            $query->where('program_id', $program);
        }

        // Grade filter
        if ($grade = $request->input('grade_filter')) {
            \Log::info('Grade filter applied', ['grade' => $grade]);
            $query->where('grade_level', $grade);
        }

        $total = $query->count();
        $filtered = $total;

        // Secure pagination with bounds
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = max(10, min($length, 100)); // Clamp to [10, 100] records per page

        $data = $query
            ->with('program')
            ->offset($start)
            ->limit($length)
            ->get(['id', 'name', 'grade_level', 'amount', 'program_id'])
            ->map(function ($item, $key) use ($start) {
                return [
                    'index' => $start + $key + 1,
                    'name' => $item->name ?? '-',
                    'applied_to_program' => $item->program->code ?? 'All Programs',
                    'applied_to_level' => $item->grade_level ?? 'All Year Levels',
                    'amount' => '₱ ' . number_format($item->amount, 2) ?? '-',
                    'id' => $item->id
                ];
            });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    // Display a listing of the resource.
    public function index(Request $request)
    {
        $schoolFees = SchoolFee::all();
        $programs = Program::all();
        $schoolSetting = \App\Models\SchoolSetting::first();

        // Get selected academic term or default to current
        $academicTermService = app(\App\Services\AcademicTermService::class);
        $selectedTermId = $request->input('term_id', 'current');

        $allTerm = $academicTermService->getAllAcademicTerms();

        if ($selectedTermId === 'current') {
            $selectedAcademicTerm = $academicTermService->fetchCurrentAcademicTerm();
        } else {
            $selectedAcademicTerm = \App\Models\AcademicTerms::find($selectedTermId);
        }

        // Calculate financial statistics for selected academic term
        // School fees are general and not tied to specific academic terms
        // Always show the total of all school fees regardless of academic term selection
        $totalSchoolFees = SchoolFee::sum('amount');
        $totalInvoices = \App\Models\Invoice::where('academic_term_id', $selectedAcademicTerm?->id)->count();
        $pendingInvoices = \App\Models\Invoice::where('academic_term_id', $selectedAcademicTerm?->id)->where('status', 'unpaid')->count();
        $paidInvoices = \App\Models\Invoice::where('academic_term_id', $selectedAcademicTerm?->id)->where('status', 'paid')->count();
        $partiallyPaidInvoices = \App\Models\Invoice::where('academic_term_id', $selectedAcademicTerm?->id)->where('status', 'partially_paid')->count();

        // Calculate total revenue from paid invoices for selected academic term
        $totalRevenue = \App\Models\Invoice::where('academic_term_id', $selectedAcademicTerm?->id)
            ->where('status', 'paid')
            ->with('items')
            ->get()
            ->sum(function ($invoice) {
                return $invoice->items->sum('amount');
            });

        return view('user-admin.school-fees.index', compact(
            'schoolFees',
            'programs',
            'schoolSetting',
            'selectedAcademicTerm',
            'totalSchoolFees',
            'totalInvoices',
            'pendingInvoices',
            'paidInvoices',
            'partiallyPaidInvoices',
            'totalRevenue',
            'allTerm'
        ));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        // For API, this might not be needed.
        return response()->json(['message' => 'Display create form']);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'program_id' => 'nullable|exists:programs,id',
                'grade_level' => 'nullable|string|max:50',
            ]);

            $schoolFee = SchoolFee::create($validated);

            // Audit logging for school fee creation
            \Log::info('School fee created', [
                'school_fee_id' => $schoolFee->id,
                'fee_name' => $schoolFee->name,
                'amount' => $schoolFee->amount,
                'created_by' => auth()->user()->id,
                'created_by_email' => auth()->user()->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Calculate updated total school fees
            $totalSchoolFees = SchoolFee::sum('amount');

            return response()->json([
                'id' => $schoolFee->id,
                'name' => $schoolFee->name,
                'amount' => $schoolFee->amount,
                'totalSchoolFees' => $totalSchoolFees
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('School fee creation failed', [
                'error' => $th->getMessage(),
                'user_id' => auth()->user()->id,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'error' => 'Failed to create school fee: ' . $th->getMessage()
            ], 422);
        }
    }

    // Display the specified resource.
    public function show($id)
    {
        $schoolFee = SchoolFee::findOrFail($id);
        return response()->json($schoolFee);
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        // For API, this might not be needed.
        return response()->json(['message' => 'Display edit form']);
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $schoolFee = SchoolFee::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'student_id' => 'sometimes|required|integer',
            // Add other fields as needed
        ]);

        $schoolFee->update($validated);

        return response()->json($schoolFee);
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $schoolFee = SchoolFee::findOrFail($id);
        $schoolFee->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
