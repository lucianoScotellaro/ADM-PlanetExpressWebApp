<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
                @auth
                    <li class="navbar-el"><x-navbar-link href="/">Home</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books">Books</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}">Profile</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books/wishlist">Wishlist</x-navbar-link></li>
                @endauth
            </x-navbar-links-list>
            <div class="nav-button-container">
                @auth
                    @can('editBooks', [\App\Models\Book::class, $user])
                    <a class="nav-btn" href="/users/user/books/create">Add book</a>
                    @endcan
                @endauth
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="user-profile-header">
            <div class="page-header left">
                @if(request()->is('users/*/books/ontrade'))
                    List of books on trade
                @elseif(request()->is('users/*/books/onloan'))
                    List of books on loan
                @elseif(request()->is('users/*/books/wishlist'))
                    Wishlist
                @elseif(request()->is('users/*/books*'))
                    Books on loan and on trade
                @endif
            </div>
            <div class="page-header">
                <a href="/users/{{ $user->id }}/books/onloan">See books on loan</a>
            </div>
            <div class="page-header">
                <a href="/users/{{ $user->id }}/books/ontrade">See books on trade</a>
            </div>
            <div class="page-header">
                <a href="/users/{{ $user->id }}/books/wishlist">See wishlist</a>
            </div>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(session()->has('success'))
            <p>{{session()->get('success')}}</p>
        @elseif(session()->has('notInListError'))
            <p>{{session()->get('notInListError')}}</p>
        @elseif(session()->has('alreadyExistsError'))
            <p>{{session()->get('alreadyExistsError')}}</p>
        @endif
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
                        @can('editBooks', [\App\Models\Book::class, $user])
                        <form action="/users/{{ $user->id }}/books/{{ $book->id }}/onloan" method="POST" id="delete-book-{{ $book->id }}-on-loan" hidden>
                            @csrf
                            @method('DELETE')
                        </form>
                        <x-form-button class="width-max" form="delete-book-{{ $book->id }}-on-loan">Delete from on loan</x-form-button>
                        <form action="/users/{{ $user->id }}/books/{{ $book->id }}/ontrade" method="POST" id="delete-book-{{ $book->id }}-on-trade" hidden>
                            @csrf
                            @method('DELETE')
                        </form>
                        <x-form-button class="width-max" form="delete-book-{{ $book->id }}-on-trade">Delete from on trade</x-form-button>
                        @endcan
                        @cannot('editBooks', [\App\Models\Book::class, $user])
                        <div class="width-max inline-form-container">
                            <x-form-button class="width-max" form="{{$book->id}}-loan-expiration">Request on loan</x-form-button>
                            <form id="{{$book->id}}-loan-expiration" action="/loans/ask/{{$user->id}}/{{$book->id}}" method="POST" class="inline-form">
                                @csrf
                                <x-form-field>
                                    <x-form-label for="expiration">Days</x-form-label>
                                    <x-form-input type="number" name="expiration" id="expiration" min="14" max="60" required/>
                                </x-form-field>
                                <x-form-error name="expiration"></x-form-error>
                            </form>
                        </div>
                        <x-button href="/trades/ask/{{$user->id}}/{{$book->id}}">Request on trade</x-button>
                            @endcannot
                    </div>
                </li>
            @endforeach
        </ul>
    </main>
</x-layout>
