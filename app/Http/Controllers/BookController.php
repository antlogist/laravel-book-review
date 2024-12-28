<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieves the 'title' and 'filter' input values from the request.
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        // Fetch all books from the database using the Book model. If a title is provided, filter the results by that title.
        $books = Book::when(
            $title,                                       // Condition: if $title is not empty or null
            fn($query, $title) => $query->title($title)   // Callback: apply the title filter to the query
        );

        // Applies a filter based on the selected option, otherwise defaults to sorting by latest.
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->withReviewsCount()->withAvgRating()->latest()
        };

        // $books = $books->get();                           // Execute the query and retrieve the results

        // Caches the result of the `$books->get()` query for 3600 seconds (1 hour).
        $cacheKey = 'books:' . $filter . ':' . $title;
        // $books = cache()->remember($cacheKey, 3600, fn() => $books->get());
        $books = Cache::remember($cacheKey, 3600, fn() => $books->get());

        // Return the 'books.index' view and pass the $books variable to it for rendering.
        return view('books.index', ['books' => $books]);

        // Another variant to return the 'books.index.view' and pass arguments
        // return view('books.index', compact('books'));
    }
    
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;

        // Load reviews sorted by creation time (most recent first)

        $book = Cache::remember(
            $cacheKey,
            3600,
            fn() => Book::with([
                'reviews' => function ($query) {
                    $query->latest();
                },
            ])->withAvgRating()->withReviewsCount()->findOrFail($id)
        );

        // $book->loadCount('reviews');
        // $book->loadAvg('reviews', 'rating');

        return view('books.show', compact('book'));
    }
}
