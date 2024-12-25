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
        // Retrieve the value of the 'title' input from the request object and assign it to the $title variable.
        $title = $request->input('title');

        // Fetch all books from the database using the Book model. If a title is provided, filter the results by that title.
        $books = Book::when(
            $title,                                       // Condition: if $title is not empty or null
            fn($query, $title) => $query->title($title)   // Callback: apply the title filter to the query
        )->get();                                         // Execute the query and retrieve the results

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
