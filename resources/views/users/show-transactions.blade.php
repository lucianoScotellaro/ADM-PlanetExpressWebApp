@php use function PHPUnit\Framework\isEmpty; @endphp
<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
                @auth
                    <li class="navbar-el">
                        <x-navbar-link href="/">Home</x-navbar-link>
                    </li>
                    <li class="navbar-el">
                        <x-navbar-link href="/users/{{ auth()->id() }}/books">Books</x-navbar-link>
                    </li>
                    <li class="navbar-el">
                        <x-navbar-link href="/users/{{ auth()->id() }}">Profile</x-navbar-link>
                    </li>
                    <li class="navbar-el">
                        <x-navbar-link href="/users/{{ auth()->id() }}/books/wishlist">Wishlist</x-navbar-link>
                    </li>
                @endauth
            </x-navbar-links-list>
            <div class="nav-button-container">
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Transactions History</p>
        </div>
    </x-slot:header>
    <main class="transactions-list-container">
        @if(!$trades->isEmpty())
            <h1>Trades</h1>
            <ul class="transactions-list">
                @foreach($trades as $trade)
                    <li class="book-list-element">
                        <div class="first-book">
                            @if(auth()->user()->is($trade->receiver))
                                <figure class="book-image-figure">
                                    <img class="book-image"
                                         src="{{ $trade->requestedBook->thumbnailUrl != null ? $trade->requestedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}"
                                         width="290" height="440" alt="Book image"/>
                                </figure>
                            @else
                                <figure class="book-image-figure">
                                    <img class="book-image"
                                         src="{{ $trade->proposedBook->thumbnailUrl != null ? $trade->proposedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}"
                                         width="290" height="440" alt="Book image"/>
                                </figure>
                            @endif
                            <div class="book-info-container">
                                <p><strong>GIVEN</strong></p>
                                <p>
                                    Title: {{auth()->user()->is($trade->receiver) ? $trade->requestedBook->title : $trade->proposedBook->title}}</p>
                                <p>
                                    Author: {{auth()->user()->is($trade->receiver) ? $trade->requestedBook->author : $trade->proposedBook->author}}</p>
                                <p>
                                    To: {{auth()->user()->is($trade->receiver) ? $trade->sender->name : $trade->receiver->name}}</p>
                            </div>
                        </div>
                        <figure class="swap-image-figure">
                            <img class="swap-image" src="{{asset('img/swap.png')}}" width="512" height="512"
                                 alt="Swap Image">
                        </figure>
                        <div class="second-book">
                            <div class="book-info-container">
                                <p><strong>GAINED</strong></p>
                                <p>
                                    Title: {{auth()->user()->is($trade->receiver) ? $trade->proposedBook->title : $trade->requestedBook->title}}</p>
                                <p>
                                    Author: {{auth()->user()->is($trade->receiver) ? $trade->proposedBook->author : $trade->requestedBook->author}}</p>
                            </div>
                            @if(auth()->user()->is($trade->receiver))
                                <figure class="book-image-figure">
                                    <img class="book-image"
                                         src="{{ $trade->proposedBook->thumbnailUrl != null ? $trade->proposedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}"
                                         width="290" height="440" alt="Book image"/>
                                </figure>
                            @else
                                <figure class="book-image-figure">
                                    <img class="book-image"
                                         src="{{ $trade->requestedBook->thumbnailUrl != null ? $trade->requestedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}"
                                         width="290" height="440" alt="Book image"/>
                                </figure>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @if(!$loans->isEmpty())
            <h1>Loans</h1>
            <ul class="transactions-list">
                @foreach($loans as $loan)
                    <li class="book-list-element">
                        <div class="first-book">
                            <figure class="book-image-figure">
                                <img class="book-image"
                                     src="{{ $loan->requestedBook->thumbnailUrl != null ? $loan->requestedBook->thumbnailUrl : asset('img/book-image-not-found.png') }}"
                                     width="290" height="440" alt="Book image"/>
                            </figure>
                            <div class="book-info-container">
                                <p><strong>{{auth()->user()->is($loan->receiver) ? 'GIVEN' : 'GAINED'}}</strong></p>
                                <p>Title: {{$loan->requestedBook->title}}</p>
                                <p>Author: {{$loan->requestedBook->author}}</p>
                                <p>{{auth()->user()->is($loan->receiver) ? 'To: '.$loan->sender->name : 'From: '.$loan->receiver->name}}</p>
                                <p>For: {{$loan->expiration}} days</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @if($trades->isEmpty() && $loans->isEmpty())
            <div class="empty-list">
                <p>You have no transactions yet.</p>
            </div>
        @endif
    </main>
</x-layout>
