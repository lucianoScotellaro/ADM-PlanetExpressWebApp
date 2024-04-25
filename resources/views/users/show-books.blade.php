@foreach($books as $book)
    <p>{{ $book->title }}</p>
    <p>{{ $book->ISBN }}</p>
@endforeach

<button type="button">
    <a href="/users/{{ $user->id }}/books/onloan/create">Add book on loan</a>
</button>
