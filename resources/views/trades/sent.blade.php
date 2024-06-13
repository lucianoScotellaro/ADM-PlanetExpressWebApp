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
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books/onwishlist">Wishlist</x-navbar-link></li>
                @endauth
            </x-navbar-links-list>
            <div class="nav-button-container">
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Sent trade requests</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(!$requests->isEmpty())
            <ul class="books-list">
                @foreach($requests as $request)
                    <li class="book-list-element">
                        <div class="first-book">
                            <figure class="book-image-figure">
                                <img class="book-image" src="{{ $request->proposedBook->thumbnailUrl != null ? $request->proposedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}" width="290" height="440" alt="Book image"/>
                            </figure>
                            <div class="book-info-container">
                                <p><strong>GIVE TO: {{$request->receiver->name}}</strong></p>
                                <p>Title: {{$request->proposedBook->title}}</p>
                                <p>Author: {{$request->proposedBook->author}}</p>
                            </div>
                        </div>
                        <figure class="swap-image-figure">
                            <img class="swap-image" src="{{asset('img/swap.png')}}" width="512" height="512" alt="Swap Image">
                        </figure>
                        <div class="second-book">
                            <div class="book-info-container">
                                <p><strong>GAIN</strong></p>
                                <p>Title: {{$request->requestedBook->title}}</p>
                                <p>Author: {{$request->requestedBook->author}}</p>
                            </div>
                            <figure class="book-image-figure">
                                <img class="book-image" src="{{ $request->requestedBook->thumbnailUrl != null ? $request->requestedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}" width="290" height="440" alt="Book image"/>
                            </figure>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>You have no sent trade requests.</p>
            </div>
        @endif
    </main>
</x-layout>
