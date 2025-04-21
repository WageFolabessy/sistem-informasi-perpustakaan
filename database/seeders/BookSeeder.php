<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
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

        for ($i = 0; $i < 20; $i++) {
            $title = ucfirst($faker->words(3, true));

            $book = Book::create([
                'title'            => $title,
                'category_id'      => $faker->randomElement($categoryIds),
                'author_id'        => $faker->randomElement($authorIds),
                'publisher_id'     => $faker->randomElement($publisherIds),
                'isbn'             => $faker->unique()->isbn13,
                'publication_year' => $faker->year,
                'synopsis'         => $faker->paragraph,
                'cover_image'      => $faker->imageUrl(640, 480, 'book', true),
                'location'         => 'Rak ' . $faker->numberBetween(1, 100),
            ]);

            BookCopy::create([
                'book_id'   => $book->id,
                'copy_code' => 'BC' . strtoupper(Str::random(8)),
                'status'    => 'Available',
                'condition' => 'Good',
            ]);
        }
    }
}
