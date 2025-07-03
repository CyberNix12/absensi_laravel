<?php

use App\Models\User;
use Illuminate\Http\Request;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new class extends Component {
    use WithFileUploads;

    public string $username = '';
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public $profile_picture = null;
    public $temporary_profile_picture = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->username = Auth::user()->username;
        $this->name = Auth::user()->name;
        $this->phone = Auth::user()->phone;
        $this->email = Auth::user()->email;
        $this->profile_picture = Auth::user()->profile_picture;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'profile_picture' => ['nullable'],
        ]);

        $user->fill($validated);

        // if ($user->isDirty('email')) {
        //     $user->email_verified_at = null;
        // }

        // if ($this->profile_picture != $request->profile_picture) {
        if ($this->profile_picture instanceof TemporaryUploadedFile) {
            $old_profile_picture = $user->profile_picture;

            if ($old_profile_picture) {
                if (file_exists('storage/' . $old_profile_picture)) {
                    unlink('storage/' . $old_profile_picture);
                }
            }

            $path = Storage::disk('public')->putFile('profile_pictures', $this->profile_picture);
            $user->profile_picture = $path;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    function hapusProfilePicture()
    {
        $user = Auth::user();
        $old_profile_picture = $user->profile_picture;

        if ($old_profile_picture) {
            if (file_exists('storage/' . $old_profile_picture)) {
                unlink('storage/' . $old_profile_picture);
            }

            $user->profile_picture = null;
            $user->save();

            $this->profile_picture = null;
        }

        $this->dispatch('profile-updated', name: Auth::user()->name);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="username" :label="__('Username')" type="text" disabled />

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <flux:input wire:model="phone" :label="__('Phone')" type="tel" required autocomplete="phone" />

            <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

            <div class="flex items-center gap-3">
                <div class="size-20">
                    {{-- @if ($profile_picture) --}}
                    <img class="w-full h-full rounded-full object-cover border"
                        src="{{ $profile_picture ? (is_string($profile_picture) ? asset('storage/' . $profile_picture) : $profile_picture->temporaryUrl()) : 'https://static-00.iconduck.com/assets.00/profile-default-icon-512x511-v4sw4m29.png' }}"
                        alt="Profile Picture" />
                    {{-- @else --}}
                </div>
                <div class="flex flex-col gap-3">
                    <flux:input wire:model="profile_picture" :label="__('Profile Picture')" type="file"
                        accept="image/*" />
                    @error('profile_picture')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <div>
                        <flux:button variant="danger" type="button" wire:click="hapusProfilePicture">
                            Hapus
                        </flux:button>
                    </div>
                </div>
            </div>

            <div>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer"
                                wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        {{-- <livewire:settings.delete-user-form /> --}}
    </x-settings.layout>
</section>
