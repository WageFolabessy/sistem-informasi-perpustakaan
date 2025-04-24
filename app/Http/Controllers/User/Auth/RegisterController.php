<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\SiteUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('user.auth.register');
    }
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $user = SiteUser::create([
                'nis' => $validatedData['nis'],
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'class' => $validatedData['class'] ?? null,
                'major' => $validatedData['major'] ?? null,
                'phone_number' => $validatedData['phone_number'] ?? null,
            ]);

            DB::commit();


            return redirect()->route('register.pending')
                ->with('status', 'Registrasi berhasil! Akun Anda (' . $validatedData['nis'] . ') sedang menunggu aktivasi oleh admin perpustakaan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Registration Error: ' . $e->getMessage());
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi atau hubungi admin.')
                ->withInput();
        }
    }
}
