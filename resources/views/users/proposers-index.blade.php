<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
            </x-navbar-links-list>
            <div class="nav-button-container">
                <a class="nav-btn" href="/users/search/form">Back to form</a>
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Proposers for this book!</p>
        </div>
    </x-slot:header>
    <main class="books-list-container">
        @if(!$proposers->isEmpty())
            <ul class="books-list">
                @foreach($proposers as $proposer)
                    <li class="book-list-element">
                        <figure class="book-image-figure">
                            <img class="book-image" src="{{asset('img/profile-image-not-found.jpg')}}" width="290" height="440" alt="Book image"/>
                        </figure>
                        <div class="book-info-container">
                            <p>Name: {{ $proposer->name }}</p>
                            <p>Email: {{ $proposer->email }}</p>
                        </div>
                        <div class="book-actions-container">
                            <x-button class="m-2" href="{{'/users/'.$proposer->id}}">See profile</x-button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="empty-list">
                <p>No proposers found.</p>
            </div>
        @endif
    </main>
</x-layout>
