<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AccountDashboardService;
use Illuminate\Http\Request;

class AccountDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,AccountDashboardService $accountDashboardService)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile dashboard data retrieved successfully',
            'data' => $accountDashboardService->getDashboardData($request->user())
        ]);
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
