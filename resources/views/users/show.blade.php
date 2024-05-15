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
                @endauth
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="user-profile-header">
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
    <main class="user-profile">
        <div class="side-content-container">
            <div class="profile-info-container">
                <figure class="profile-image-figure">
                    <img class="profile-image" src="{{ asset('img/user-image-not-found.jpg') }}" width="1244" height="1063" alt="User image" />
                </figure>
                <p>Name: {{ $user->name }}</p>
                <p>Email: {{ $user->email }}</p>
                <p>Rating: Coming soon</p>
            </div>
            <a class="nav-btn" href="#">Rate User!</a>
        </div>
        <div class="main-content-container">
            <div class="last-transactions">
                <div class="transaction-header">
                    <p> Last Transactions </p>
                </div>
                <div class="transaction-container">
                    <p></p>
                </div>
                <div class="transaction-container">
                    <p></p>
                </div>
                <div class="transaction-container">
                    <p></p>
                </div>
            </div>
        </div>
    </main>
</x-layout>
