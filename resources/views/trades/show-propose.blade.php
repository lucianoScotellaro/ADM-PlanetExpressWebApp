<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
            </x-navbar-links-list>
            <div class="nav-button-container">
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Select a book to trade!</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(!$books->isEmpty())
            <ul class="books-list">
                @foreach($books as $book)
                    <li class="book-list-element">
                        <figure class="book-image-figure">
                            <img class="book-image" src="{{ $book->thumbnailUrl != null ? $book->thumbnailUrl : asset('img/book-image-not-found.png') }}" width="290" height="440" alt="Book image"/>
                        </figure>
                        <div class="book-info-container">
                            <p>Title: {{ $book->title }}</p>
                            <p>Author: {{ $book->author }}</p>
                            <p>ID: {{ $book->id }}</p>
                        </div>
                        <div class="book-actions-container">
                            <form id="propose-{{$book->id}}-form" method="POST" action="/trades/propose/{{$book->id}}" hidden>
                                @csrf
                            </form>
                            <x-form-button form="propose-{{$book->id}}-form">Propose</x-form-button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>You have no books to trade.</p>
            </div>
        @endif
    </main>
</x-layout>

