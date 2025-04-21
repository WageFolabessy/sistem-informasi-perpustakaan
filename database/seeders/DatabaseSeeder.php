<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        AdminUser::truncate();
        Category::truncate();

        Schema::enableForeignKeyConstraints();

        AdminUser::factory(1)->create();
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
