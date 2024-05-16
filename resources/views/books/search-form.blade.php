<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <x-navbar-links-list>
            </x-navbar-links-list>
            <div class="nav-button-container">
                @auth
                    <a class="nav-btn" href="/">Back to home</a>
                @endauth
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Search your favourite books!</p>
        </div>
    </x-slot:header>
    <main class="search">
        <form method="GET" action="/books/search" class="search-form">
            <div class="form-header">
                <p>Fill the form and find your book!</p>
            </div>
            <div class="form-fields">
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
                @if(session()->has('noParametersError'))
                    <p class="form-footer text-error">
                        At least one parameter is required
                    </p>
                @else
                    <p class="form-footer">
                        At least one parameter is required
                    </p>
                @endif
            </div>
            <input type="number" name="pageNumber" id="pageNumber" value="1" hidden/>
            <x-form-button>Search</x-form-button>
        </form>
    </main>
</x-layout>
