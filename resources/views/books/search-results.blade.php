@foreach($books as $book)
    <p>{{ $book->title }}</p>
    <p>{{ $book->ISBN }}</p>
    <form method="POST" action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/onloan" id="add-book-on-loan" hidden>
        @csrf
    </form>
    <button type="submit" form="add-book-on-loan"> Add book </button>
@endforeach


