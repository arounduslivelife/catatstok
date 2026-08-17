<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('workspace_id', auth()->user()->workspace_id)
            ->where('role', 'staff')
            ->get();
        return response()->json($staff);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            return response()->json(['message' => 'Unauthorized. Only owner can add staff.'], 403);
        }

        $validated = $request->validate([
            'phone' => 'required|unique:users',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $staff = User::create([
            'workspace_id' => auth()->user()->workspace_id,
            'phone' => $validated['phone'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
        ]);

        ActivityLog::log('CREATE_STAFF', 'Mendaftarkan staff baru: ' . $staff->username);

        return response()->json($staff, 201);
    }
}
