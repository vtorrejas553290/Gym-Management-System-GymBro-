<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index()
    {
        return view('admin.membership-plans');
    }

    public function getData()
    {
        $plans = MembershipPlan::all();
        return response()->json($plans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'duration_days' => 'required|integer',
            'price' => 'required|numeric',
            'features' => 'required|array',
            'popular' => 'boolean',
            'active' => 'boolean',
        ]);
        
        $plan = MembershipPlan::create($validated);
        return response()->json($plan, 201);
    }

    public function update(Request $request, $id)
    {
        $plan = MembershipPlan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'duration_days' => 'required|integer',
            'price' => 'required|numeric',
            'features' => 'required|array',
            'popular' => 'boolean',
            'active' => 'boolean',
        ]);
        
        $plan->update($validated);
        return response()->json($plan);
    }

    public function destroy($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();
        return response()->json(null, 204);
    }
}