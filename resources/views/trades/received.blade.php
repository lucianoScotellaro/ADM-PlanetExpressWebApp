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
            <p>Received trade requests</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(session()->has('success'))
            <div class="message-container text-success">
                <p>{{session()->get('success')}}</p>
            </div>
        @endif
        @if(session()->has('invalidRequestError'))
            <div class="message-container text-error">
                <p>{{session()->get('invalidRequestError')}}</p>
            </div>
        @endif
        @if(session()->has('invalidBookError'))
            <div class="message-container text-error">
                <p>{{session()->get('invalidBookError')}}</p>
            </div>
        @endif
        @if(!$requests->isEmpty())
            <ul class="books-list">
                @foreach($requests as $request)
                    <li class="book-list-element">
                        <div class="first-book">
                            <figure class="book-image-figure">
                                <img class="book-image" src="{{ $request->requestedBook->thumbnailUrl != null ? $request->requestedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}" width="290" height="440" alt="Book image"/>
                            </figure>
                            <div class="book-info-container">
                                <p><strong>GIVE</strong></p>
                                <p>Title: {{$request->requestedBook->title}}</p>
                                <p>Author: {{$request->requestedBook->author}}</p>
                                <p>Requested by: <strong>{{$request->sender->name}}</strong></p>
                            </div>
                        </div>
                        <figure class="swap-image-figure">
                            <img class="swap-image" src="{{asset('img/swap.png')}}" width="512" height="512" alt="Swap Image">
                        </figure>
                        <div class="second-book">
                            <div class="book-info-container">
                                <p><strong>GAIN</strong></p>
                                <p>Title: {{$request->proposedBook->title}}</p>
                                <p>Author: {{$request->proposedBook->author}}</p>
                            </div>
                            <figure class="book-image-figure">
                                <img class="book-image" src="{{ $request->proposedBook->thumbnailUrl != null ? $request->proposedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}" width="290" height="440" alt="Book image"/>
                            </figure>
                        </div>
                        <div class="book-actions-container actions-small">
                            <x-button href="/trades/requests/accept/{{$request->sender->id}}/{{$request->requestedBook->id}}/{{$request->proposedBook->id}}">Accept</x-button>
                            <x-button href="/trades/requests/refuse/{{$request->sender->id}}/{{$request->requestedBook->id}}/{{$request->proposedBook->id}}">Refuse</x-button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>There are no trade requests for you.</p>
            </div>
        @endif
    </main>
</x-layout>

