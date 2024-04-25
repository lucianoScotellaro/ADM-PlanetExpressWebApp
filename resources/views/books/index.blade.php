@foreach($books as $book)
<form method="POST">
    <button>
        <a href="/users/{{ $user->id }}/{{ $book->ISBN }}/ontrade">Aggiungi</a>
    </button>
</form>
@endforeach

