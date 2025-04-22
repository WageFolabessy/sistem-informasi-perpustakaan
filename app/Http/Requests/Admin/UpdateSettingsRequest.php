<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.loan_duration' => ['required', 'integer', 'min:1'],
            'settings.max_loan_books' => ['required', 'integer', 'min:1'],
            'settings.fine_rate_per_day' => ['required', 'integer', 'min:0'],
            'settings.booking_expiry_days' => ['required', 'integer', 'min:1'],
            'settings.library_name' => ['required', 'string', 'max:255'],
            'settings.library_address' => ['nullable', 'string'],
            'settings.*' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'settings.required' => 'Data pengaturan tidak boleh kosong.',
            'settings.array' => 'Format data pengaturan tidak valid.',

            'settings.loan_duration.required' => 'Durasi Peminjaman (hari) wajib diisi.',
            'settings.loan_duration.integer' => 'Durasi Peminjaman (hari) harus angka.',
            'settings.loan_duration.min' => 'Durasi Peminjaman (hari) minimal 1.',

            'settings.max_loan_books.required' => 'Batas Peminjaman Buku wajib diisi.',
            'settings.max_loan_books.integer' => 'Batas Peminjaman Buku harus angka.',
            'settings.max_loan_books.min' => 'Batas Peminjaman Buku minimal 1.',

            'settings.fine_rate_per_day.required' => 'Tarif Denda per Hari wajib diisi.',
            'settings.fine_rate_per_day.integer' => 'Tarif Denda per Hari harus angka.',
            'settings.fine_rate_per_day.min' => 'Tarif Denda per Hari tidak boleh negatif.',

            'settings.booking_expiry_days.required' => 'Masa Berlaku Booking (hari) wajib diisi.',
            'settings.booking_expiry_days.integer' => 'Masa Berlaku Booking (hari) harus angka.',
            'settings.booking_expiry_days.min' => 'Masa Berlaku Booking (hari) minimal 1.',

            'settings.library_name.required' => 'Nama Perpustakaan wajib diisi.',
            'settings.library_name.max' => 'Nama Perpustakaan maksimal 255 karakter.',
        ];
    }
}
