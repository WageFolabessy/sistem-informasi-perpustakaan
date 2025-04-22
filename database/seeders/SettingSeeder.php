<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate(); // DB::table('settings')->delete();

        $settings = [
            [
                'key' => 'library_name',
                'value' => 'Perpustakaan SMK NEGERI 1 SANGGGAU LEDO',
                'description' => 'Nama resmi perpustakaan yang ditampilkan di situs.',
            ],
            [
                'key' => 'library_address',
                'value' => 'Jalan Duren Tio Dusun Jawa RT 001 RW 001, Lembang, Kecamatan Sanggau Ledo, Kabupaten Bengkayang, Kalimantan Barat',
                'description' => 'Alamat lengkap perpustakaan.',
            ],
            [
                'key' => 'library_contact_email',
                'value' => 'perpustakaan@smkn1sanngauledo.sch.id',
                'description' => 'Alamat email kontak perpustakaan.',
            ],
            [
                'key' => 'library_contact_phone',
                'value' => '6281347024823',
                'description' => 'Nomor telepon kontak perpustakaan.',
            ],
            [
                'key' => 'loan_duration',
                'value' => '2',
                'description' => 'Durasi standar peminjaman buku (dalam hari).',
            ],
            [
                'key' => 'max_loan_books',
                'value' => '2',
                'description' => 'Jumlah maksimal buku yang boleh dipinjam siswa secara bersamaan.',
            ],
            [
                'key' => 'fine_rate_per_day',
                'value' => '1000',
                'description' => 'Tarif denda keterlambatan per buku per hari (dalam Rupiah).',
            ],
            [
                'key' => 'booking_expiry_days',
                'value' => '2',
                'description' => 'Batas waktu pengambilan buku yang sudah dibooking (dalam hari).',
            ],
            [
                'key' => 'max_fine_amount',
                'value' => '50000',
                'description' => 'Batas maksimal jumlah denda per peminjaman (Rp). Isi 0 jika tidak ada batas.',
            ],
            [
                'key' => 'lost_book_fee',
                'value' => '75000',
                'description' => 'Biaya penggantian/denda tetap untuk buku yang hilang (Rp). Isi 0 jika tidak ada.'
            ]
            // Tambahkan pengaturan lain jika ada, misalnya:
            // [
            //     'key' => 'lost_book_fee_percentage',
            //     'value' => '100', // Default 100% harga buku (jika ada data harga)
            //     'description' => 'Persentase denda penggantian buku hilang dari harga buku.',
            // ],
        ];

        foreach ($settings as $settingData) {
            Setting::updateOrCreate(
                ['key' => $settingData['key']],
                [
                    'value' => $settingData['value'],
                    'description' => $settingData['description']
                ]
            );
        }

        DB::table('settings')->delete();
        DB::table('settings')->insert($settings);
    }
}
