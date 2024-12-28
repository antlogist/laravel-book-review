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
        class="input">{{ isset($review) ? $review : old('review') }}</textarea>

    @error('review')
    <div class="text-red-600/100 font-normal text-xs mb-2">{{ $message }}</div>
    @enderror

    <label for="rating">Rating</label>
    <select name="rating" id="rating" class="input">
        <option value="">Select Rating</option>
        @for ($rating = 1; $rating <= 5; $rating++)
            <option value="{{ $rating }}">{{ $rating }}</option>
            @endfor
    </select>

    @error('rating')
    <div class="text-red-600/100 font-normal text-xs ">{{ $message }}</div>
    @enderror

    <div class="mt-4">
        <button type="submit" class="btn">Add Review</button>
    </div>
</form>

@endsection