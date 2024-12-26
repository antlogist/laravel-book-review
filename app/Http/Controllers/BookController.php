<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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

        $books = $books->get();                           // Execute the query and retrieve the results

        // Return the 'books.index' view and pass the $books variable to it for rendering.
        return view('books.index', ['books' => $books]);

        // Another variant to return the 'books.index.view' and pass arguments
        // return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
