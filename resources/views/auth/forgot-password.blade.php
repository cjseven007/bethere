<x-guest-layout>
    <p class="text-muted mb-3">
        {{ __('Forgot your password? No problem. Just enter your email below and we’ll send you a reset link.') }}
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email address') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-envelope-at me-1"></i>{{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
