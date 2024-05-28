<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
            </x-navbar-links-list>
            <div class="nav-button-container">
                <a class="nav-btn" href="/users/user/books/create">Back to form</a>
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Those are the books found!</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
    @if(!empty($books))
        <ul class="books-list">
            @foreach($books as $book)
                <li class="book-list-element">
                    <figure class="book-image-figure">
                        <img class="book-image" src="{{$book['thumbnailUrl'] != null ? $book['thumbnailUrl'] : asset('img/book-image-not-found.png')}}" width="290" height="440" alt="Book image"/>
                    </figure>
                    <div class="book-info-container">
                        <p>Title: {{ $book['title'] }}</p>
                        <p>Author: {{ $book['authors'] != null ? $book['authors'][0] : 'Not Available' }}</p>
                        <p>Publish date: {{ $book['publishedDate'] != null ? $book['publishedDate'] : 'Not Available' }}</p>
                    </div>
                    <div class="book-actions-container">
                        <form method="POST" action="/users/{{ $user->id }}/books/{{ $book['id'] }}/onloan" id="add-book-{{ $book['id'] }}-on-loan" hidden>
                            @csrf
                        </form>
                        <x-form-button class="width-max" form="add-book-{{ $book['id'] }}-on-loan">Add to books on loan</x-form-button>
                        <form method="POST" action="/users/{{ $user->id }}/books/{{ $book['id'] }}/ontrade" id="add-book-{{ $book['id'] }}-on-trade" hidden>
                            @csrf
                        </form>
                        <x-form-button class="width-max" form="add-book-{{ $book['id'] }}-on-trade">Add to books on trade</x-form-button>
                    </div>
                </li>
            @endforeach
            <li class="pagination-buttons-container">
                <div class="previous-btn">
                    @if($currentPageNumber > 1)
                        <x-button href="{{ preg_replace('/&pageNumber=[0-9]*/','&pageNumber='.($currentPageNumber - 1),request()->getRequestUri())}}">Previous</x-button>
                    @endif
                </div>
                <div class="next-btn">
                    <x-button href="{{ preg_replace('/&pageNumber=[0-9]*/','&pageNumber='.($currentPageNumber + 1),request()->getRequestUri())}}">Next</x-button>
                </div>
            </li>
        </ul>
    @else
        <div class="empty-list">
            <p>No results found. Try with other parameters.</p>
        </div>
        @if($currentPageNumber > 1)
        <div class="pagination-buttons">
            <x-button href="{{ preg_replace('/&pageNumber=[0-9]*/','&pageNumber='.($currentPageNumber - 1),request()->getRequestUri())}}">Previous</x-button>
        </div>
        @endif
    @endif
    </main>
</x-layout>



