<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Phone -->
        <div class="mt-2">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone" pattern="[4569]{1}[0-9]{7}" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
         <!-- Company Name -->
         <div class="mt-2">
            <x-input-label for="company" :value="__('Company')" />
            <x-text-input id="company" class="block mt-1 w-full" type="text" name="company" :value="old('company')" required/>
            <x-input-error :messages="$errors->get('company')" class="mt-2" />
        </div>
        <!-- Position -->
{{-- <div class="mt-2">
    <x-input-label for="position" :value="__('Position')" />
    <select id="position" name="position" class="block mt-1 w-full" required>
        <option value="owner" @if(old('position') === 'owner') selected @endif>Owner</option>
        <option value="user" @if(old('position') === 'user') selected @endif>User</option>
        <option value="accountant" @if(old('position') === 'accountant') selected @endif>Accountant</option>
        <option value="manager" @if(old('position') === 'manager') selected @endif>Manager</option>
    </select>
    <x-input-error :messages="$errors->get('position')" class="mt-2" />
</div> --}}

        <!-- Password -->
        <div class="mt-2">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
