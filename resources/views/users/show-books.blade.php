<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
                @auth
                    <li class="navbar-el"><x-navbar-link href="/">Home</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books">Books</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}">Profile</x-navbar-link></li>
                    <li class="navbar-el" id="requests-link">
                        <x-navbar-link class="no-underline" href="#">Requests</x-navbar-link>
                        <div class="requests-nav-links-container" id="requests-nav-links-container">
                            <p>Trade</p>
                            <ul class="no-style width-max">
                                <li class="request-nav-el"><x-navbar-link href="/trades/requests/received">Received</x-navbar-link></li>
                                <li class="request-nav-el margin-bottom-sm"><x-navbar-link href="/trades/requests/sent">Sent</x-navbar-link></li>
                            </ul>
                            <p class="border-top-primary">Loan</p>
                            <ul class="no-style width-max">
                                <li class="request-nav-el"><x-navbar-link href="/loans/requests/received">Received</x-navbar-link></li>
                                <li class="request-nav-el margin-bottom-sm"><x-navbar-link href="/loans/requests/sent">Sent</x-navbar-link></li>
                            </ul>
                        </div>
                    </li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/transactions">Transactions</x-navbar-link></li>
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
                @elseif(request()->is('users/*/books/onwishlist'))
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
                <a href="/users/{{ $user->id }}/books/onwishlist">See wishlist</a>
            </div>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(session()->has('success'))
            <div class="message-container text-success">
                <p>{{session()->get('success')}}</p>
            </div>
        @elseif(session()->has('notInListError'))
            <div class="message-container text-error">
                <p>{{session()->get('notInListError')}}</p>
            </div>
        @elseif(session()->has('alreadyExistsError'))
            <div class="message-container text-error">
                <p>{{session()->get('alreadyExistsError')}}</p>
            </div>
        @elseif(session()->has('message'))
            <div class="message-container text-success">
                <p>{{session()->get('message')}}</p>
            </div>
        @endif
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
                            @can('editBooks', [\App\Models\Book::class, $user])
                                @if($book->pivot->onLoan)
                                    <form action="/users/{{ $user->id }}/books/{{ $book->id }}/onloan" method="POST" id="delete-book-{{ $book->id }}-on-loan" hidden>
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <x-form-button class="width-max" form="delete-book-{{ $book->id }}-on-loan">Delete from on loan</x-form-button>
                                @endif
                                @if($book->pivot->onTrade)
                                    <form action="/users/{{ $user->id }}/books/{{ $book->id }}/ontrade" method="POST" id="delete-book-{{ $book->id }}-on-trade" hidden>
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <x-form-button class="width-max" form="delete-book-{{ $book->id }}-on-trade">Delete from on trade</x-form-button>
                                @endif
                                @if($book->pivot->onWishlist)
                                    <form action="/users/{{ $user->id }}/books/{{ $book->id }}/onwishlist" method="POST" id="delete-book-{{ $book->id }}-on-wishlist" hidden>
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <x-form-button class="width-max" form="delete-book-{{ $book->id }}-on-wishlist">Delete from wishlist</x-form-button>
                                @endif
                            @endcan
                            @cannot('editBooks', [\App\Models\Book::class, $user])
                                @if($book->pivot->onLoan)
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
                               @endif
                               @if($book->pivot->onTrade)
                                    <x-button href="/trades/ask/{{$user->id}}/{{$book->id}}">Request on trade</x-button>
                               @endif
                            @endcannot
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>There are no books in this list.</p>
            </div>
        @endif
    </main>
</x-layout>
