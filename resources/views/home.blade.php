<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
                <li class="navbar-el"><x-navbar-link href="/">Home</x-navbar-link></li>
                <li class="navbar-el"><x-navbar-link href="#">Books</x-navbar-link></li>
                <li class="navbar-el"><x-navbar-link href="#">Profile</x-navbar-link></li>
                <li class="navbar-el"><x-navbar-link href="#">Wishlist</x-navbar-link></li>
            </x-navbar-links-list>
            <div class="nav-button-container">
                <a class="nav-btn" href="/register">Sign Up</a>
                <a class="nav-btn" href="/login">Login</a>
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
    </x-slot:header>
    <main class="home" style="background-image: url('{{ asset('img/home-background.jpg') }}')">
    </main>
</x-layout>
