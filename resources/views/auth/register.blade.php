<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <div class="nav-button-container">
                <a class="nav-btn" href="/">Back home</a>
                <a class="nav-btn" href="/login">Login</a>
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Enter in Planet Express!</p>
        </div>
    </x-slot:header>
    <main class="register" style="background-image: url('{{ asset('img/home-background.jpg') }}')">
        <form action="/register" method="POST" class="signup-form">
            @csrf
            <div class="form-header">
                <p>Register Now!</p>
                <x-close-form-button></x-close-form-button>
            </div>
            <x-form-field>
                <x-form-label for="name">Name</x-form-label>
                <x-form-input
                    type="text"
                    name="name"
                    placeholder="Your name..."
                    :value="old('name')"
                    required
                />
            </x-form-field>
            <x-form-error name="name"></x-form-error>
            <x-form-field>
                <x-form-label for="email">Email</x-form-label>
                <x-form-input
                    type="email"
                    name="email"
                    placeholder="Your email..."
                    :value="old('email')"
                    required
                />
            </x-form-field>
            <x-form-error name="email"></x-form-error>
            <x-form-field>
                <x-form-label for="password">Password</x-form-label>
                <x-form-input
                    type="password"
                    name="password"
                    placeholder="Password..."
                    :value="old('password')"
                    required
                />
            </x-form-field>
            <x-form-error name="password"></x-form-error>
            <x-form-field>
                <x-form-label for="password_confirmation"
                >Confirm Password</x-form-label
                >
                <x-form-input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm password"
                    required
                />
            </x-form-field>
            <x-form-error name="password_confirmation"></x-form-error>
            <x-form-button>Register</x-form-button>
            <p class="form-footer">
                Already have an account? <a href="/login">Login</a>
            </p>
        </form>
    </main>
</x-layout>


