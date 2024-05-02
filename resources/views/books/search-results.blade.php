@foreach($books as $book)
    <p>{{ $book->title }}</p>
    <p>{{ $book->ISBN }}</p>
    <form method="POST" action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/onloan" id="add-book-{{ $book->ISBN }}-on-loan" hidden>
        @csrf
    </form>
    <button type="submit" form="add-book-{{ $book->ISBN }}-on-loan"> Add book on loan</button>
    <form method="POST" action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/ontrade" id="add-book-{{ $book->ISBN }}-on-trade" hidden>
        @csrf
    </form>
    <button type="submit" form="add-book-{{ $book->ISBN }}-on-trade"> Add book on trade</button>
@endforeach


