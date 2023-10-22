<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(10)->create()->each(function ($book) {
            $countReviews = random_int(3, 10);
            Review::factory()->count($countReviews)->goodRating()->for($book)->create();
        });

        Book::factory(15)->create()->each(function ($book) {
            $countReviews = random_int(3, 10);
            Review::factory()->count($countReviews)->averageRating()->for($book)->create();
        });

        Book::factory(20)->create()->each(function ($book) {
            $countReviews = random_int(3, 10);
            Review::factory()->count($countReviews)->badRating()->for($book)->create();
        });
    }
}
