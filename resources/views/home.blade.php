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
                @guest
                @endguest
            </x-navbar-links-list>
            <div class="nav-button-container">
                @auth
                    <a class="nav-btn" href="/users/search/form">Search book</a>
                    <form id="logout" action="/logout" method="POST" hidden>
                        @csrf
                    </form>
                    <button class="nav-btn" type="submit" form="logout">Logout</button>
                @endauth
                @guest
                    <a class="nav-btn" href="/register">Sign Up</a>
                    <a class="nav-btn" href="/login">Login</a>
                @endguest
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
    </x-slot:header>
    <main class="home" style="background-image: url('{{ asset('img/home-background.jpg') }}')">
    </main>
</x-layout>
