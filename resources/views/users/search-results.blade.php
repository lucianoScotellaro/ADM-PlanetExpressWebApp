<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
            </x-navbar-links-list>
            <div class="nav-button-container">
                <a class="nav-btn" href="/users/search/form">Back to form</a>
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Those are the books found!</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(!$books->isEmpty())
            <ul class="books-list">
                @foreach($books as $book)
                    <li class="book-list-element">
                        <figure class="book-image-figure">
                            <img class="book-image" src="{{$book->thumbnailUrl != null ? $book->thumbnailUrl : asset('img/book-image-not-found.png')}}" width="290" height="440" alt="Book image"/>
                        </figure>
                        <div class="book-info-container">
                            <p>Title: {{ $book->title }}</p>
                            <p>Author: {{ $book->author }}</p>
                            <p>Publish date: {{ $book->publishedDate }}</p>
                        </div>
                        <div class="book-actions-container">
                            @if($searchOn === 'proposedBook')
                                <x-button href="{{'/users/search/proposers/'.$book->id}}">See proposers for this book</x-button>
                            @elseif($searchOn === 'requestedBook')
                                <x-button href="{{'/users/search/claimers/'.$book->id}}">See claimers for this book</x-button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>No results found.</p>
            </div>
        @endif
    </main>
</x-layout>
