<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        return view('admin.trainers');
    }

    public function getData()
    {
        $trainers = Trainer::all();
        return response()->json($trainers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'specialization' => 'required|string',
            'experience' => 'required|integer|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
        ]);
        
        $trainer = Trainer::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'specialization' => $validated['specialization'],
            'experience' => $validated['experience'],
            'hourly_rate' => $validated['hourly_rate'],
            'status' => $validated['status'],
        ]);
        
        return response()->json($trainer, 201);
    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
            'specialization' => 'required|string',
            'experience' => 'required|integer|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
        ]);
        
        $updateData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'specialization' => $validated['specialization'],
            'experience' => $validated['experience'],
            'hourly_rate' => $validated['hourly_rate'],
            'status' => $validated['status'],
        ];
        
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }
        
        $trainer->update($updateData);
        return response()->json($trainer);
    }

    public function updateRate(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);
        $request->validate([
            'hourly_rate' => 'required|numeric|min:0',
        ]);
        $trainer->update(['hourly_rate' => $request->hourly_rate]);
        return response()->json($trainer);
    }

    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->delete();
        return response()->json(null, 204);
    }
}