<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Pastikan model Category sudah ada dan dikonfigurasi dengan benar.
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Perjalanan',
                'description' => 'Buku-buku yang berkisah tentang pengalaman perjalanan dan petualangan.'
            ],
            [
                'name'        => 'Sejarah',
                'description' => 'Buku-buku tentang peristiwa, tokoh, dan perkembangan masa lalu.'
            ],
            [
                'name'        => 'Teknologi',
                'description' => 'Buku-buku mengenai perkembangan teknologi dan inovasi modern.'
            ],
            [
                'name'        => 'Bisnis',
                'description' => 'Buku-buku yang membahas strategi bisnis, manajemen, dan kewirausahaan.'
            ],
            [
                'name'        => 'Motivasi',
                'description' => 'Buku-buku yang menginspirasi dan memotivasi untuk mencapai potensi maksimal.'
            ],
            [
                'name'        => 'Filsafat',
                'description' => 'Buku-buku yang mendalami pemikiran filosofis dan refleksi kehidupan.'
            ],
            [
                'name'        => 'Seni',
                'description' => 'Buku-buku mengenai seni rupa, musik, dan kreativitas.'
            ],
            [
                'name'        => 'Misteri',
                'description' => 'Buku-buku penuh teka-teki dan misteri yang menegangkan.'
            ],
            [
                'name'        => 'Fantasi',
                'description' => 'Buku-buku imajinatif dengan dunia yang penuh keajaiban dan magis.'
            ],
            [
                'name'        => 'Romantis',
                'description' => 'Buku-buku yang mengisahkan tentang cinta dan hubungan emosional.'
            ],
            [
                'name'        => 'Thriller',
                'description' => 'Buku-buku dengan alur tegang dan penuh suspense.'
            ],
            [
                'name'        => 'Horor',
                'description' => 'Buku-buku yang menakutkan dan menghadirkan ketakutan.'
            ],
            [
                'name'        => 'Biografi',
                'description' => 'Buku-buku yang mengisahkan perjalanan hidup tokoh-tokoh penting.'
            ],
            [
                'name'        => 'Ilmu Pengetahuan',
                'description' => 'Buku-buku yang mengungkap fakta dan pengetahuan ilmiah.'
            ],
            [
                'name'        => 'Pendidikan',
                'description' => 'Buku-buku yang mendukung proses belajar mengajar dan pengembangan pendidikan.'
            ],
            [
                'name'        => 'Psikologi',
                'description' => 'Buku-buku yang membahas perilaku, emosi, dan pikiran manusia.'
            ],
            [
                'name'        => 'Olahraga',
                'description' => 'Buku-buku mengenai dunia olahraga, teknik, dan motivasi atlet.'
            ],
            [
                'name'        => 'Kuliner',
                'description' => 'Buku-buku tentang resep, teknik memasak, dan budaya kuliner.'
            ],
            [
                'name'        => 'Pengembangan Diri',
                'description' => 'Buku-buku yang memberikan panduan untuk pengembangan diri dan peningkatan kualitas hidup.'
            ],
            [
                'name'        => 'Hiburan',
                'description' => 'Buku-buku yang memberikan kesenangan, tawa, dan relaksasi.'
            ],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['description'],
            ]);
        }
    }
}
