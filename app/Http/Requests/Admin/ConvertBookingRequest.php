<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\BookCopy;
use App\Enum\BookingStatus;
use App\Enum\BookCopyStatus;

class ConvertBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!Auth::guard('admin')->check()) {
            return false;
        }
        $booking = $this->route('booking');
        return $booking instanceof Booking && $booking->status === BookingStatus::Active;
    }

    public function rules(): array
    {
        $booking = $this->route('booking');

        return [
            'book_copy_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($booking) {
                    $copy = BookCopy::find($value);
                    if (!$copy) {
                        $fail('Eksemplar buku yang dipilih tidak ditemukan.');
                    } elseif ($copy->book_id !== $booking->book_id) {
                        $fail('Eksemplar yang dipilih tidak cocok dengan buku yang dibooking.');
                    } elseif ($copy->status !== BookCopyStatus::Available) {
                        $fail('Eksemplar buku yang dipilih sedang tidak tersedia.');
                    }
                },
            ],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_copy_id.required' => 'Eksemplar buku wajib dipilih untuk dikonversi.',
            'book_copy_id.integer' => 'ID Eksemplar tidak valid.',
            'admin_notes.string' => 'Catatan konversi harus berupa teks.',
            'admin_notes.max' => 'Catatan konversi terlalu panjang (maksimal 1000 karakter).',
        ];
    }
}
