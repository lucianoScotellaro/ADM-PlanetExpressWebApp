<x-layout>
    <x-slot:header>
        Planet Express
    </x-slot:header>
    <x-slot:title>
        Register
    </x-slot:title>

    <div class="flex w-full h-full justify-center items-center">
        <div class="border-solid border-2 border-black rounded-lg">
            <div class="bg-gray-300">
                <form class="m-6" action="/register" method="POST">
                    @csrf
                    <x-form-field>
                        <x-form-label for="name"> Name </x-form-label>
                        <x-form-input :value="old('name')" type="string" id="name" name="name" required></x-form-input>
                        <x-form-error name="name"></x-form-error>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="email"> Email </x-form-label>
                        <x-form-input :value="old('email')" type="email" id="email" name="email" required></x-form-input>
                        <x-form-error name="email"></x-form-error>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="password"> Password </x-form-label>
                        <x-form-input type="password" id="password" name="password" required></x-form-input>
                        <x-form-error name="password"></x-form-error>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="password_confirmation"> Confirm Password </x-form-label>
                        <x-form-input type="password" id="password_confirmation" name="password_confirmation" required></x-form-input>
                        <x-form-error name="password_confirmation"></x-form-error>
                    </x-form-field>

                    <div class="flex justify-center">
                        <x-form-button class="mt-3"> Register </x-form-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>


