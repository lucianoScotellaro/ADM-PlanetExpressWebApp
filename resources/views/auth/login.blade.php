<x-layout>
    <x-slot:navbar>
        <x-navbar>
            <div class="nav-button-container">
                <a class="nav-btn" href="/">Back home</a>
                <a class="nav-btn" href="/login">Register</a>
            </div>
        </x-navbar>
    </x-slot:navbar>
    <x-slot:header>
        <div class="page-header">
            <p>Login and share your books!</p>
        </div>
    </x-slot:header>
    <main class="login" style="background-image: url('{{ asset('img/ship.jpg') }}')">
        <form action="/login" method="POST" class="login-form">
            @csrf
            <div class="form-header">
                <p>Login Now!</p>
            </div>
            <x-form-field>
                <x-form-label for="email">Email</x-form-label>
                <x-form-input
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="Email..."
                    required
                />
            </x-form-field>
            <x-form-field>
                <x-form-label for="password">Password</x-form-label>
                <x-form-input
                    type="password"
                    name="password"
                    placeholder="Password..."
                    required
                />
            </x-form-field>
            <x-form-error name="credentials-error"></x-form-error>
            <x-form-button>Login</x-form-button>
            <p class="form-footer">
                Don't have an account? <a href="/register">Register</a>
            </p>
        </form>
    </main>
</x-layout>
