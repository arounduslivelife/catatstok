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
    
    if (Auth::attempt($credentials, $request->has('remember'))) {
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

Route::get('/forgot-password', function () {
    return view('auth.forgot-password', ['hideNav' => true]);
})->name('forgot-password');

Route::post('/forgot-password', function(Request $request) {
    $request->validate(['phone' => 'required']);
    $user = User::where('phone', $request->phone)->first();
    
    if (!$user) {
        return back()->withErrors(['phone' => 'Nomor HP tidak terdaftar.']);
    }

    $newPassword = \Illuminate\Support\Str::random(6);
    $user->password = Hash::make($newPassword);
    $user->save();

    $apiKey = \App\Models\Setting::where('key', 'wa_api_key')->value('value');
    $sender = \App\Models\Setting::where('key', 'wa_sender_number')->value('value');
    
    if ($apiKey && $sender) {
        $messageBody = "Halo {$user->username}, password baru Anda untuk aplikasi CatatStok adalah:\n\n*{$newPassword}*\n\nSilakan login dan segera ganti password Anda demi keamanan.";
        try {
            $response = \Illuminate\Support\Facades\Http::post('https://app.botgateway.my.id/send-message', [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $user->phone,
                'message' => $messageBody
            ]);
            
            \App\Models\WaMessageLog::create([
                'phone_number' => $user->phone,
                'message' => $messageBody,
                'status' => $response->successful() ? 'success' : 'failed',
                'response_data' => $response->body()
            ]);
        } catch (\Exception $e) {
            \App\Models\WaMessageLog::create([
                'phone_number' => $user->phone,
                'message' => $messageBody,
                'status' => 'failed',
                'response_data' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    return back()->with('success', 'Password baru telah dikirim ke WhatsApp Anda.');
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
    Route::get('/products/{product}/edit', [App\Http\Controllers\Web\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\Web\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\Web\ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/transactions/create', [App\Http\Controllers\Web\TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [App\Http\Controllers\Web\TransactionController::class, 'store'])->name('transactions.store');

    Route::get('/transactions/{transaction}/edit', [App\Http\Controllers\Web\TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [App\Http\Controllers\Web\TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [App\Http\Controllers\Web\TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/reports', [App\Http\Controllers\Web\ReportController::class, 'index'])->name('reports.index');

    Route::get('/staff', [App\Http\Controllers\Web\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [App\Http\Controllers\Web\StaffController::class, 'store'])->name('staff.store');
    
    Route::post('/change-password', function(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password berhasil diubah!');
    })->name('change-password');
});

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function() {
    Route::get('/dashboard', [App\Http\Controllers\Web\SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::post('/workspaces/{workspace}/toggle', [App\Http\Controllers\Web\SuperAdminController::class, 'toggleStatus']);
    Route::post('/workspaces/{workspace}/set-date', [App\Http\Controllers\Web\SuperAdminController::class, 'setSubscription']);
    Route::post('/workspaces/{workspace}/start-trial', [App\Http\Controllers\Web\SuperAdminController::class, 'startTrial']);
    Route::get('/workspaces/{workspace}/logs', [App\Http\Controllers\Web\SuperAdminController::class, 'logs'])->name('superadmin.logs');
    Route::get('/wa-logs', [App\Http\Controllers\Web\SuperAdminController::class, 'waLogs'])->name('superadmin.wa-logs');
    Route::post('/change-password', [App\Http\Controllers\Web\SuperAdminController::class, 'changePassword'])->name('superadmin.change-password');
    Route::post('/save-settings', [App\Http\Controllers\Web\SuperAdminController::class, 'saveSettings'])->name('superadmin.save-settings');
});
