<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Workspace;

Route::get('/', function () {
    return view('welcome', ['hideNav' => true]);
});

Route::get('/login', function () {
    return view('auth.login', ['hideNav' => true]);
})->name('login');

Route::post('/login', function(Request $request) {
    $credentials = $request->validate([
        'phone' => 'required',
        'password' => 'required'
    ]);
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            return redirect()->intended('superadmin/dashboard');
        }
        
        if ($user->workspace && !$user->workspace->status) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['phone' => 'Akses ditolak: Perusahaan Anda sedang di-suspend. Hubungi Super Admin.']);
        }
        
        // Cek masa aktif langganan (Jika null atau expired, tolak akses)
        if ($user->workspace && (!$user->workspace->subscription_ends_at || $user->workspace->subscription_ends_at < now())) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['phone' => 'Akun belum aktif atau masa berlangganan telah habis.']);
        }
        
        return redirect()->intended('dashboard');
    }
    
    return back()->withErrors([
        'phone' => 'Kredensial salah.',
    ]);
});

Route::get('/register', function () {
    return view('auth.register', ['hideNav' => true]);
});

Route::post('/register', function(Request $request) {
    $request->validate([
        'phone' => 'required|unique:users',
        'username' => 'required|unique:users',
        'password' => 'required|min:6',
    ]);
    
    DB::beginTransaction();
    try {
        $workspace = Workspace::create([
            'name' => 'Workspace ' . $request->username
        ]);
        $user = User::create([
            'workspace_id' => $workspace->id,
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'owner'
        ]);
        $workspace->update(['created_by' => $user->id]);
        DB::commit();
        
        Auth::login($user);
        return redirect('/dashboard');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
    }
});

Route::post('/logout', function(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', function () {
        $products = \App\Models\Product::all();
        return view('dashboard', compact('products'));
    })->name('dashboard');

    Route::get('/products', [App\Http\Controllers\Web\ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [App\Http\Controllers\Web\ProductController::class, 'store'])->name('products.store');

    Route::get('/transactions/create', [App\Http\Controllers\Web\TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [App\Http\Controllers\Web\TransactionController::class, 'store'])->name('transactions.store');

    Route::get('/transactions/{transaction}/edit', [App\Http\Controllers\Web\TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [App\Http\Controllers\Web\TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [App\Http\Controllers\Web\TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/reports', [App\Http\Controllers\Web\ReportController::class, 'index'])->name('reports.index');

    Route::get('/staff', [App\Http\Controllers\Web\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [App\Http\Controllers\Web\StaffController::class, 'store'])->name('staff.store');
});

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function() {
    Route::get('/dashboard', [App\Http\Controllers\Web\SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::post('/workspaces/{workspace}/toggle', [App\Http\Controllers\Web\SuperAdminController::class, 'toggleStatus']);
    Route::post('/workspaces/{workspace}/set-date', [App\Http\Controllers\Web\SuperAdminController::class, 'setSubscription']);
    Route::post('/workspaces/{workspace}/start-trial', [App\Http\Controllers\Web\SuperAdminController::class, 'startTrial']);
    Route::post('/change-password', [App\Http\Controllers\Web\SuperAdminController::class, 'changePassword'])->name('superadmin.change-password');
});
