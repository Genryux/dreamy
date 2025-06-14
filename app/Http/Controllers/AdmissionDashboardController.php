<?php

namespace App\Http\Controllers;

use App\Services\DashboardDataService;
use Illuminate\Http\Request;

class AdmissionDashboardController extends Controller
{
    protected $dashboardDataService;

    public function __construct(DashboardDataService $dashboardDataService)
    {

        $this->dashboardDataService = $dashboardDataService;

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->dashboardDataService->getAdmissionDashboardData();

        // Fallback values in case the data is not set
        if ($data) {
            return view('user-applicant.dashboard', [
                'activeEnrollmentPeriod' => $data['activeEnrollmentPeriod'] ?? null,
                'currentAcadTerm' => $data['currentAcadTerm'] ?? null,
                'applicant' => $data['applicant'] ?? null,
            ]);
        }

        return null;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
