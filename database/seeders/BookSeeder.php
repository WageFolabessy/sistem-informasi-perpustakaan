<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        $kataSifatJudul = ["Rahasia", "Misteri", "Petualangan", "Kisah", "Jejak", "Cinta", "Harapan", "Bayangan", "Legenda", "Takdir", "Senja", "Fajar"];
        $kataBendaJudul = ["Nusantara", "Pulau Terpencil", "Kota Tua", "Gunung Mistis", "Pintu Ajaib", "Harta Karun", "Masa Lalu", "Kerajaan Hilang", "Lembah Sunyi", "Bintang Jatuh", "Sungai Waktu"];
        $kataTempatJudul = ["di Ufuk Barat", "di Tanah Leluhur", "di Balik Awan", "di Dasar Lautan", "di Negeri Dongeng", "di Antara Dua Dunia"];
        $kataKeteranganJudul = ["Yang Hilang", "Yang Terlupakan", "Abadi", "Terakhir", "Sejati"];

        $awalSinopsis = [
            "Buku ini bercerita tentang",
            "Mengisahkan perjalanan luar biasa",
            "Sebuah cerita epik mengenai",
            "Dalam buku ini, kita mengikuti kisah",
            "Tersembunyi di balik kabut waktu, terdapat kisah",
            "Di jantung kota metropolis {kota}, hiduplah",
            "Jauh di pelosok desa {kota}, dimulai sebuah",
        ];
        $pengenalanTokoh = [
            "seorang {pekerjaan} pemberani bernama {nama}",
            "keluarga {namaBelakang} yang menyimpan rahasia",
            "petualang muda bernama {nama}",
            "dua sahabat, {nama1} dan {nama2},",
            "sosok misterius {nama}",
            "seorang {pekerjaan} tua yang bijaksana,",
            "gadis yatim piatu bernama {nama},",
        ];
        $pengembanganPlot = [
            "yang tanpa sengaja menemukan {bendaAjaib}",
            "dalam misinya mencari {bendaPusaka} legendaris",
            "ketika mereka terseret ke dalam {konflikBesar}",
            "saat mencoba mengungkap {misteriGelap}",
            "perjalanan mereka dipenuhi dengan {rintanganTakTerduga}",
            "takdir mempertemukan mereka dengan {sekutuTakTerduga}",
            "mereka harus berpacu dengan waktu sebelum {ancamanBesar} terjadi",
            "di tengah perjalanan, {pengkhianatan} mengubah segalanya",
        ];
        $konflikPuncak = [
            "menghadapi {musuhBebuyutan} dalam pertarungan akhir",
            "mengorbankan segalanya demi {tujuanMulia}",
            "membuat pilihan sulit antara {pilihanA} dan {pilihanB}",
            "mengungkap kebenaran yang mengejutkan tentang {masaLalu}",
            "bertahan hidup di tengah {bencanaAlam} yang dahsyat",
            "mencari jalan pulang dari {dimensiLain}",
        ];
        $penutupSinopsis = [
            "Akankah {nama} berhasil mencapai tujuannya?",
            "Perjalanan ini akan menguji batas keberanian dan kesetiaan mereka.",
            "Sebuah kisah yang akan membekas di hati pembaca.",
            "Temukan akhir yang tak terduga dalam cerita ini.",
            "Cinta, persahabatan, dan pengorbanan menjadi inti dari narasi ini.",
            "Takdir dunia berada di tangan mereka.",
        ];

        $pekerjaanList = ['pemberani', 'penyihir', 'ksatria', 'ilmuwan', 'detektif', 'pedagang', 'petani', 'nelayan', 'pengembara'];
        $bendaAjaibList = ['peta kuno', 'artefak misterius', 'kristal ajaib', 'buku sihir', 'ramuan langka', 'jimat keberuntungan'];
        $konflikList = ['perang saudara', 'intrik kerajaan', 'kutukan kuno', 'konspirasi gelap', 'perburuan harta'];
        $rintanganList = ['medan berbahaya', 'musuh licik', 'teka-teki rumit', 'badai dahsyat', 'keraguan diri'];
        $musuhList = ['penjahat kejam', 'makhluk mistis', 'kekuatan gelap', 'kerajaan tiran', 'organisasi rahasia'];

        $categoryIds  = Category::pluck('id')->toArray();
        $authorIds    = Author::pluck('id')->toArray();
        $publisherIds = Publisher::pluck('id')->toArray();

        if (empty($categoryIds)) {
            $categoryIds = [null];
        }
        if (empty($authorIds)) {
            $authorIds = [null];
        }
        if (empty($publisherIds)) {
            $publisherIds = [null];
        }

        $publicCoverDir = 'book_covers';
        $absoluteCoverDir = storage_path('app/public/' . $publicCoverDir);

        if (!is_dir($absoluteCoverDir)) {
            if (mkdir($absoluteCoverDir, 0755, true)) {
                Log::info("BookSeeder: Created directory: " . $absoluteCoverDir);
            } else {
                Log::error("BookSeeder: Failed to create directory: " . $absoluteCoverDir);
            }
        }
        if (!is_writable($absoluteCoverDir)) {
            Log::error("BookSeeder: Directory not writable: " . $absoluteCoverDir);
        }

        $usedTitles = []; 

        for ($i = 0; $i < 20; $i++) {

            $title = '';
            $tryCount = 0;
            do {
                $tipeJudul = mt_rand(1, 4);
                $namaFaker1 = $faker->firstName;
                $namaFaker2 = $faker->lastName;

                switch ($tipeJudul) {
                    case 1:
                        $title = $faker->randomElement($kataSifatJudul) . ' ' . $faker->randomElement($kataBendaJudul);
                        break;
                    case 2:
                        $title = $faker->randomElement($kataSifatJudul) . ' ' . $namaFaker1;
                        break;
                    case 3:
                        $title = $faker->randomElement(['Petualangan', 'Jejak', 'Kisah']) . ' ' . $namaFaker1 . ' ' . $faker->randomElement($kataTempatJudul);
                        break;
                    case 4:
                    default:
                        $title = $faker->randomElement($kataBendaJudul) . ' ' . $faker->randomElement($kataKeteranganJudul);
                        break;
                }
                $tryCount++;
            } while (in_array($title, $usedTitles) && $tryCount < 5); 
            $usedTitles[] = $title;

            $nama1 = $faker->firstName;
            $nama2 = $faker->firstName;
            $namaBelakang = $faker->lastName;
            $kota = $faker->city;
            $pekerjaan = $faker->randomElement($pekerjaanList);
            $bendaAjaib = $faker->randomElement($bendaAjaibList);
            $bendaPusaka = $faker->randomElement($bendaAjaibList);
            $konflikBesar = $faker->randomElement($konflikList);
            $misteriGelap = $faker->randomElement($konflikList);
            $rintanganTakTerduga = $faker->randomElement($rintanganList);
            $musuhBebuyutan = $faker->randomElement($musuhList);
            $ancamanBesar = $faker->randomElement($musuhList);

            $sinopsis = $faker->randomElement($awalSinopsis) . ' ' .
                $faker->randomElement($pengenalanTokoh) . '. ' .
                $faker->randomElement($pengembanganPlot) . '. ' .
                $faker->randomElement($konflikPuncak) . '. ' .
                $faker->randomElement($penutupSinopsis);

            $sinopsis = str_replace(
                ['{nama}', '{nama1}', '{nama2}', '{namaBelakang}', '{kota}', '{pekerjaan}', '{bendaAjaib}', '{bendaPusaka}', '{konflikBesar}', '{misteriGelap}', '{rintanganTakTerduga}', '{sekutuTakTerduga}', '{ancamanBesar}', '{pengkhianatan}', '{musuhBebuyutan}', '{tujuanMulia}', '{pilihanA}', '{pilihanB}', '{masaLalu}', '{bencanaAlam}', '{dimensiLain}'],
                [$nama1, $nama1, $nama2, $namaBelakang, $kota, $pekerjaan, $bendaAjaib, $bendaPusaka, $konflikBesar, $misteriGelap, $rintanganTakTerduga, $faker->firstName, $ancamanBesar, 'sebuah pengkhianatan', $musuhBebuyutan, 'tujuan mulia', 'pilihan sulit A', 'pilihan sulit B', 'masa lalu mereka', 'bencana dahsyat', 'dimensi lain'],
                $sinopsis
            );
            // --------------------------------------------------

            $coverImagePath = null;

            try {
                $externalImageUrl = $faker->imageUrl(640, 480, 'book', true);
                $fileContents = @file_get_contents($externalImageUrl);

                if ($fileContents === false) {
                    $externalImageUrl = 'https://placehold.co/640x480.png?text=' . urlencode(substr($title, 0, 20)); // Ambil sebagian judul
                    $fileContents = @file_get_contents($externalImageUrl);
                }

                if ($fileContents === false) {
                    Log::error("BookSeeder: Failed to download image content from both sources for title: " . $title);
                } else {
                    $imageInfo = @getimagesizefromstring($fileContents);
                    $extension = 'png';
                    if ($imageInfo && isset($imageInfo['mime'])) {
                        $mime = $imageInfo['mime'];
                        $mimeMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
                        if (isset($mimeMap[$mime])) {
                            $extension = $mimeMap[$mime];
                        }
                    }
                    $filename = Str::random(15) . '.' . $extension;
                    $filePath = $absoluteCoverDir . '/' . $filename;
                    if (file_put_contents($filePath, $fileContents) !== false) {
                        $coverImagePath = $publicCoverDir . '/' . $filename;
                    } else {
                        Log::error("BookSeeder: Failed to save image file to: " . $filePath);
                    }
                }
            } catch (\Exception $e) {
                Log::error("BookSeeder: Exception during image processing for title '{$title}': " . $e->getMessage());
            }

            $book = Book::create([
                'title'            => $title,
                'category_id'      => $faker->randomElement($categoryIds),
                'author_id'        => $faker->randomElement($authorIds),
                'publisher_id'     => $faker->randomElement($publisherIds),
                'isbn'             => $faker->unique()->isbn13,
                'publication_year' => $faker->year,
                'synopsis'         => $sinopsis,
                'cover_image'      => $coverImagePath,
                'location'         => 'Rak ' . $faker->numberBetween(1, 100),
            ]);

            BookCopy::create([
                'book_id'   => $book->id,
                'copy_code' => 'BC' . strtoupper(Str::random(8)),
                'status'    => 'Available',
                'condition' => 'Good',
            ]);
        }
        Log::info('BookSeeder: Seeding process finished.');
    }
}
