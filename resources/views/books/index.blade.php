@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>

<!-- Search by title form -->
<form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">

    <!-- Sets the initial value of the input field to the 'title' parameter from the current HTTP request. -->
    <input type="text" name="title" placeholder="Search by title"
        value="{{ request('title') }}" class="input h-10" />

    <!-- Passes the current 'filter' query parameter to a hidden form field. -->
    <input type="hidden" name="filter" value="{{ request('filter') }}" />

    <button type="submit" class="btn h-10">Search</button>
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>

</form>
<!-- / Search form -->

<!-- Filter -->
<div class="filter-container mb-4 flex">
    @php
    $filters = [
    '' => 'Latest',
    'popular_last_month' => 'Popular Last Month',
    'popular_last_6months' => 'Popular Last 6 Months',
    'highest_rated_last_month' => 'Highest Rated Last Month',
    'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
    ];
    @endphp

    @foreach ($filters as $key => $label)
    <!-- Generates a URL for the 'books.index' route with the current query parameters and an additional 'filter' parameter set to '$key'. -->
    <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
        class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
        {{ $label }}
    </a>
    @endforeach
</div>
<!-- / Filter -->

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
                <div>
                    <div class="book-rating">
                        {{ number_format($book->reviews_avg_rating, 2, ',', '.') }}
                    </div>
                    <div class="book-review-count">
                        out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                    </div>
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