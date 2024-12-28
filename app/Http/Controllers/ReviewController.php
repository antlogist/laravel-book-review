<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Book;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Book $book)
    {
        return view('books.reviews.create', ['book' => $book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request, Book $book)
    {
        $book->reviews()->create($request->all());
        return redirect()->route('books.show', $book);
    }
}
