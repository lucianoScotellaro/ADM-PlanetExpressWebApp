<x-layout>
    <x-slot:header>
        <div class="page-header">
            Login
        </div>
    </x-slot:header>
    <main>
        <form action="/login" method="POST" class="login-form">
            <div class="form-header">
                <p>Login Now!</p>
                <x-close-form-button></x-close-form-button>
            </div>
            <x-form-field>
                <x-form-label for="username">Username</x-form-label>
                <x-form-input
                    type="text"
                    name="username"
                    :value="old('username')"
                    placeholder="Username..."
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
            <x-form-button>Login</x-form-button>
            <p class="form-footer">
                Don't have an account? <a href="/register">Register</a>
            </p>
        </form>
    </main>
</x-layout>
