<?php

namespace App\Http\Controllers\Admin;

use App\Enum\BorrowingStatus;
use App\Events\UserAccountActivated;
use App\Http\Controllers\Controller;
use App\Models\SiteUser;
use App\Http\Requests\Admin\StoreSiteUserRequest;
use App\Http\Requests\Admin\UpdateSiteUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiteUserController extends Controller
{
    public function index(): View
    {
        $siteUsers = SiteUser::latest()->get();
        return view('admin.site-users.index', compact('siteUsers'));
    }

    public function create(): View
    {
        return view('admin.site-users.create');
    }

    public function show(SiteUser $siteUser): View
    {
        $siteUser->load(['borrowings' => function ($query) {
            $query->with(['bookCopy' => function ($qCopy) {
                $qCopy->with('book:id,title');
            }])->latest('borrow_date');
        }]);

        return view('admin.site-users.show', compact('siteUser'));
    }

    public function store(StoreSiteUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        DB::beginTransaction();
        try {
            $siteUser = SiteUser::create($validated);

            $siteUser->is_active = true;
            $siteUser->save();

            DB::commit();

            return redirect()->route('admin.site-users.index')
                ->with('success', 'Data siswa baru berhasil ditambahkan dan diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(SiteUser $siteUser): View
    {
        return view('admin.site-users.edit', compact('siteUser'));
    }

    public function update(UpdateSiteUserRequest $request, SiteUser $siteUser): RedirectResponse
    {
        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $siteUser->is_active = $request->has('is_active');
        unset($validated['is_active']);

        $siteUser->update($validated);
        $siteUser->save();

        return redirect()->route('admin.site-users.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(SiteUser $siteUser): RedirectResponse
    {
        $activeBorrowings = $siteUser->borrowings()
            ->whereIn('status', [
                BorrowingStatus::Borrowed,
                BorrowingStatus::Overdue
            ])
            ->exists();

        if ($activeBorrowings) {
            return redirect()->route('admin.site-users.index')
                ->with('error', 'Gagal menghapus! Siswa ' . $siteUser->name . ' masih memiliki pinjaman aktif atau lewat tempo.');
        }

        try {
            $userName = $siteUser->name;
            $siteUser->delete();
            return redirect()->route('admin.site-users.index')
                ->with('success', 'Data siswa ' . $userName . ' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.site-users.index')
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    public function pendingRegistrations(): View
    {
        $pendingUsers = SiteUser::where('is_active', false)->latest()->get();
        return view('admin.site-users.pending', compact('pendingUsers'));
    }

    public function activate(SiteUser $siteUser): RedirectResponse
    {
        if (!$siteUser->is_active) {
            $siteUser->is_active = true;
            $siteUser->save();

            event(new UserAccountActivated($siteUser));

            return redirect()->route('admin.site-users.pending')
                ->with('success', 'Akun siswa ' . $siteUser->name . ' berhasil diaktifkan.');
        }
        return redirect()->route('admin.site-users.pending')
            ->with('warning', 'Akun siswa ' . $siteUser->name . ' sudah aktif.');
    }

    public function reject(SiteUser $siteUser): RedirectResponse
    {
        if (!$siteUser->is_active) {
            $userName = $siteUser->name;
            $siteUser->delete();
            return redirect()->route('admin.site-users.pending')
                ->with('success', 'Registrasi siswa ' . $userName . ' berhasil ditolak dan dihapus.');
        }
        return redirect()->route('admin.site-users.pending')
            ->with('warning', 'Tidak dapat menolak akun yang sudah aktif.');
    }
}
