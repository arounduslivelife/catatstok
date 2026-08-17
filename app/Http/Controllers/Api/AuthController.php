<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $workspace = Workspace::create([
                'name' => 'Workspace ' . $request->username,
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

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->role === 'superadmin') {
            return response()->json([
                'message' => 'Superadmin tidak dapat login melalui aplikasi mobile.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['phone' => 'required']);
        $user = User::where('phone', $request->phone)->first();
        
        if (!$user) {
            return response()->json(['message' => 'Nomor HP tidak terdaftar.'], 404);
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

        return response()->json(['message' => 'Password baru telah dikirim ke WhatsApp Anda.']);
    }
}
