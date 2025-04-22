<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Enum\FineStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class FineController extends Controller
{
    public function index(Request $request): View
    {
        $fines = Fine::with([
            'borrowing:id,site_user_id,book_copy_id',
            'borrowing.siteUser:id,nis,name',
            'borrowing.bookCopy:id,copy_code,book_id',
            'borrowing.bookCopy.book:id,title',
            'paymentProcessor:id,name'
        ])
            ->orderByRaw("FIELD(status, '" . FineStatus::Unpaid->value . "', '" . FineStatus::Paid->value . "', '" . FineStatus::Waived->value . "')")
            ->latest('created_at')
            ->get();

        return view('admin.fines.index', compact('fines'));
    }

    public function pay(Request $request, Fine $fine): RedirectResponse
    {
        if ($fine->status !== FineStatus::Unpaid) {
            return redirect()->back()->with('error', 'Denda ini sudah lunas atau dibebaskan.');
        }

        DB::beginTransaction();
        try {
            $fine->status = FineStatus::Paid;
            $fine->paid_amount = $fine->amount;
            $fine->payment_date = now();
            $fine->admin_user_id_paid = Auth::guard('admin')->id();
            // Anda bisa menambahkan field 'payment_notes' jika perlu dari request
            // $fine->notes = $request->input('payment_notes');
            $fine->save();

            DB::commit();

            return redirect()->route('admin.fines.index')
                ->with('success', 'Pembayaran denda berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.fines.index')
                ->with('error', 'Gagal mencatat pembayaran denda: ' . $e->getMessage());
        }
    }

    public function waive(Request $request, Fine $fine): RedirectResponse
    {
        if ($fine->status !== FineStatus::Unpaid) {
            return redirect()->back()->with('error', 'Denda ini sudah lunas atau dibebaskan.');
        }

        DB::beginTransaction();
        try {
            $fine->status = FineStatus::Waived;
            $fine->paid_amount = 0;
            $fine->payment_date = now();
            $fine->admin_user_id_paid = Auth::guard('admin')->id();
            // Anda bisa menambahkan field 'waiver_notes' jika perlu
            // $fine->notes = $request->input('waiver_notes', 'Denda dibebaskan oleh admin.');
            $fine->save();

            DB::commit();

            return redirect()->route('admin.fines.index')
                ->with('success', 'Denda berhasil dibebaskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.fines.index')
                ->with('error', 'Gagal membebaskan denda: ' . $e->getMessage());
        }
    }
}
