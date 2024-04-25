@foreach($books as $book)
    <p>{{ $book->title }}</p>
    <p>{{ $book->ISBN }}</p>
    <form action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/onloan" method="POST" id="delete-book-on-loan">
        @csrf
        @method('DELETE')
    </form>
    <button type="submit" form="delete-book-on-loan">Delete</button>
@endforeach

<button type="button">
    <a href="/users/{{ $user->id }}/books/onloan/create">Add book on loan</a>
</button>
