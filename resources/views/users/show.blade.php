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
                    <img class="profile-image" src="{{ asset('img/profile-image-not-found.jpg') }}" width="1244" height="1063" alt="User image" />
                </figure>
                <p>Name: {{ $user->name }}</p>
                <p>Email: {{ $user->email }}</p>
                <p>Rating: {{ $user->averageRating }}</p>
            </div>
            <div class="review-buttons-container">
                @can('reviewUser',[\App\Models\Review::class, $user])
                <button class="nav-btn" type="button" id="rate-user-btn">Rate User!</button>
                @endcan
                <a class="nav-btn" href="/reviews/{{ $user->id }}">See reviews on him</a>
            </div>
        </div>
        <div class="main-content-container" id="profile-user-main">
            @if(session()->has('message'))
                <div class="message-container text-success">
                    <p>{{session()->get('message')}}</p>
                </div>
            @endif
            <form method="POST" action="/reviews/{{ $user->id }}" class="rate-user-form">
                @csrf
                <div class="form-header">
                    <p>Leave a comment!</p>
                    <button id="close-form-btn" class="close-form-btn" type="button" style="background-image: url('{{ asset('svg/close-icon.svg') }}')"></button>
                </div>
                <div class="form-fields">
                    <div class="form-fields-row stars-field">
                        <x-form-field>
                            <x-form-label for="rating">Rating - From 1 to 5</x-form-label>
                            <x-form-input class="stars-input" type="number" name="rating" min="1" max="5" :value="old('number')" required placeholder="1"/>
                        </x-form-field>
                    </div>
                    <x-form-error name="rating"></x-form-error>
                    <div class="form-fields-row">
                        <x-form-field>
                            <x-form-label for="comment">Comment</x-form-label>
                            <textarea class="form-input comment-input" id="comment" name="comment" placeholder="Leave a comment on the user..."></textarea>
                        </x-form-field>
                    </div>
                    <x-form-error name="comment"></x-form-error>
                </div>
                <x-form-button>Rate!</x-form-button>
            </form>
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
