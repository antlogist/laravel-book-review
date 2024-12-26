<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    private static $filterTitle = '';

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        self::$filterTitle = $title;
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ]);
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        // return $query->withCount('reviews')->orderBy('reviews_count', 'desc');
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {
        // return $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to),
        ], 'rating')->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews)
    {
        // return $query->withCount('reviews')->having('reviews_count', '>=', $minReviews)->orderBy('reviews_count', 'desc');
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } else if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } else if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    // Applies filters to the query to find popular, highly-rated books with at least 2 reviews in the last month.
    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    // Filters the query to include popular, highly-rated books with at least 3 reviews in the past 6 months.
    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(3);
    }

    // Retrieves books that were highest rated and popular in the last month, with a minimum of 2 reviews.
    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    // Retrieves books that were highest rated and popular in the last 6 months, with a minimum of 3 reviews.
    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(3);
    }

    protected static function booted(): void
    {

        $filters = [
            '',
            'popular_last_month',
            'popular_last_6months',
            'highest_rated_last_month',
            'highest_rated_last_6months'
        ];

        static::updated(
            fn(Book $book) => self::clearCache($book, $filters)
        );

        static::deleted(
            fn(Book $book) => self::clearCache($book, $filters)
        );
    }

    private static function clearCache(Book $book, array $filters): void
    {
        cache()->forget('book:' . $book->id);

        array_map(fn($filter) => cache()->forget('books:' . $filter . ':' . self::$filterTitle), $filters);
    }
}
