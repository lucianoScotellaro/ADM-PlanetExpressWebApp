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
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="user-profile-header">
            <div class="page-header left">
                Reviews for this user!
            </div>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(session()->has('message'))
            <div class="message-container text-success">
                <p>{{session()->get('message')}}</p>
            </div>
        @endif
        @if(!$reviews->isEmpty())
            <ul class="books-list">
                @foreach($reviews as $review)
                    <li class="book-list-element">
                        <figure class="profile-image-figure">
                            <img class="profile-image" src="{{ asset('img/profile-image-not-found.jpg') }}" width="1244" height="1063" alt="User image" />
                        </figure>
                        <div class="book-info-container justify-text padding-right-sm flex-col-wrap">
                            <p>From: {{ $review->reviewer->name }}</p>
                            <p>Rating: {{ $review->rating }}</p>
                            <p>Comment: {{ $review->comment }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>There are no reviews for this user.</p>
            </div>
        @endif
    </main>
</x-layout>
