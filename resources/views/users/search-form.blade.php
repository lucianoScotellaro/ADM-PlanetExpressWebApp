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
                    <li class="navbar-el"><x-navbar-link href="/users/{{ auth()->id() }}/books/onwishlist">Wishlist</x-navbar-link></li>
                @endauth
            </x-navbar-links-list>
            <div class="nav-button-container">
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Search your favourite books!</p>
        </div>
    </x-slot:header>
    <main class="search">
        <form method="GET" action="/users/search" class="search-form">
            <div class="form-header">
                <p>Fill the form and find your book!</p>
            </div>
            <div class="form-fields with-select">
                <div class="form-fields-row">
                    <x-form-field>
                        <x-form-label for="title">Title</x-form-label>
                        <x-form-input
                            type="text"
                            name="title"
                            placeholder="Book title..."
                            :value="old('title')"
                        />
                    </x-form-field>
                </div>
                <div class="form-fields-row">
                    <x-form-field>
                        <x-form-label for="author">Author</x-form-label>
                        <x-form-input
                            type="text"
                            name="author"
                            placeholder="Book author..."
                            :value="old('title')"
                        />
                    </x-form-field>
                    <x-form-field>
                        <x-form-label for="category">Category</x-form-label>
                        <x-form-input
                            type="text"
                            name="category"
                            placeholder="Book category..."
                            :value="old('category')"
                        />
                    </x-form-field>
                </div>
                <div class="form-fields-row margin-top-sm">
                    <x-form-field>
                        <select title="searchOn" class="form-select" name="searchOn" id="searchOn">
                            <option selected disabled>Search for</option>
                            <option value="requestedBook">Requested book</option>
                            <option value="proposedBook">Proposed book</option>
                        </select>
                    </x-form-field>
                </div>
                <p class="form-footer margin-top-sm">
                    Search on field must be set
                </p>
            </div>
            <label for="pageNumber" hidden>
                <input type="number" name="pageNumber" id="pageNumber" value="1"/>
            </label>
            <x-form-button>Search</x-form-button>
        </form>
    </main>
</x-layout>
