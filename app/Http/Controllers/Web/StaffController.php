<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'owner') {
            return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya Owner yang bisa mengakses halaman ini.');
        }
        
        $staff = User::where('workspace_id', auth()->user()->workspace_id)
            ->where('id', '!=', auth()->id())
            ->get();
            
        return view('staff', compact('staff'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            return back()->with('error', 'Hanya Owner yang bisa menambah karyawan.');
        }

        $request->validate([
            'phone' => 'required|string|unique:users,phone',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'workspace_id' => auth()->user()->workspace_id,
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);

        return back()->with('success', 'Karyawan berhasil ditambahkan.');
    }
}
