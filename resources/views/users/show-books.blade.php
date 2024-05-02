@foreach($books as $book)
    <p>{{ $book->title }}</p>
    <p>{{ $book->ISBN }}</p>
    <form action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/onloan" method="POST" id="delete-book-{{ $book->ISBN }}-on-loan">
        @csrf
        @method('DELETE')
    </form>
    <button type="submit" form="delete-book-{{ $book->ISBN }}-on-loan">Delete from loans</button><br>
    <form action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/ontrade" method="POST" id="delete-book-{{ $book->ISBN }}-on-trade">
        @csrf
        @method('DELETE')
    </form>
    <button type="submit" form="delete-book-{{ $book->ISBN }}-on-trade">Delete from trades</button><br>
@endforeach

<button type="button">
    <a href="/users/user/books/create">Add book</a>
</button>
