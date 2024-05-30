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
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Received loan requests</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(session()->has('success'))
            <div class="message-container text-success">
                <p>{{session()->get('success')}}</p>
            </div>
        @endif
        @if(!$requests->isEmpty())
            <ul class="books-list">
                @foreach($requests as $request)
                    <li class="book-list-element">
                        <figure class="book-image-figure">
                            <img class="book-image" src="{{ $request->requestedBook->thumbnailUrl != null ? $request->requestedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}" width="290" height="440" alt="Book image"/>
                        </figure>
                        <div class="book-info-container">
                            <p>Title: {{$request->requestedBook->title}}</p>
                            <p>Author: {{$request->requestedBook->author}}</p>
                            <p>ID: {{$request->requestedBook->id}}</p>
                            <p>Requested by: <strong>{{$request->sender->name}}</strong></p>
                            <p>For: {{$request->expiration}} days</p>
                        </div>
                        <div class="book-actions-container">
                            <x-button href="/loans/requests/accept/{{$request->sender->id}}/{{$request->requestedBook->id}}">Give on loan</x-button>
                            <x-button href="/loans/requests/refuse/{{$request->sender->id}}/{{$request->requestedBook->id}}">Refuse request</x-button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>There are no loan requests for you.</p>
            </div>
        @endif
    </main>
</x-layout>

