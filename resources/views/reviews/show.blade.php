<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
                @auth
                    <li class="navbar-el"><x-navbar-link href="/">Home</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books">Books</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}">Profile</x-navbar-link></li>
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books/onwishlist">Wishlist</x-navbar-link></li>
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
