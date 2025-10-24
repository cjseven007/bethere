<x-guest-layout>
    <p class="text-muted mb-3">
        {{ __("Thanks for signing up! Before getting started, please verify your email by clicking the link we just sent you. If you didn't receive the email, we can send another.") }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
