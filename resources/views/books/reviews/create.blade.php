@extends('layouts.app')

@section('content')

<h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>
<form method="POST" action="{{ route('books.reviews.store', $book) }}">
    @csrf
    <label for="review">Review</label>
    <textarea
        id="review"
        type="text"
        name="review"
        required
        class="input mb-4">
    </textarea>

    <label for="rating">Rating</label>
    <select name="rating" id="rating" class="input mb-4">
        @for ($rating = 1; $rating <= 5; $rating++)
            <option value="{{ $rating }}">{{ $rating }}</option>
        @endfor
    </select>

    <button type="submit" class="btn">Add Review</button>
</form>

@endsection