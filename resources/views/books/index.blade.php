@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>

<!-- Search by title form -->
<form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">

    <!-- Sets the initial value of the input field to the 'title' parameter from the current HTTP request. -->
    <input type="text" name="title" placeholder="Search by title"
        value="{{ request('title') }}" class="input h-10" />
    
    <button type="submit" class="btn h-10">Search</button>
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
    
</form>
<!-- / Search form -->

<!-- Book list -->
<ul>
    @forelse ($books as $book)
    <li class="mb-4">
        <div class="book-item">
            <div
                class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                    <a href="#" class="book-title">{{ $book->title }}</a>
                    <span class="book-author">by {{ $book->author }}</span>
                </div>
            </div>
        </div>
    </li>
    @empty
    <li class="mb-4">
        <div class="empty-book-item">
            <p class="empty-text">No books found</p>
            <a href="#" class="reset-link">Reset criteria</a>
        </div>
    </li>
    @endforelse
</ul>
<!-- / Book list -->
@endsection