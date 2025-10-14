<x-guest-layout>
    <style>
        .verify-wrapper {max-width: 760px; margin: 2rem auto; background: #fff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,.08); padding: 28px; color: #1f2937;}
        .verify-title {font-size: 1.25rem; font-weight: 600; margin-bottom: .5rem;}
        .verify-info {font-size: .98rem; line-height: 1.65;}
        .verify-status {margin-top: 1rem; padding: 12px 14px; border-radius: 10px; background: #ecfdf5; color: #065f46; font-weight: 600;}
        .verify-actions {display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 1.25rem;}
        .btn-primary {background: #2e7d32; color: #fff; border: none; border-radius: 10px; padding: .65rem 1rem; box-shadow: 0 4px 12px rgba(46,125,50,.25); transition: transform .08s ease, box-shadow .2s ease, background .2s ease;}
        .btn-primary:hover {background: #256d27; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(46,125,50,.3);}        
        .logout-link {color: #374151; text-decoration: none; font-weight: 600; padding: .55rem .9rem; border-radius: 10px; border: 1px solid #e5e7eb; background: #f9fafb; transition: background .2s ease, box-shadow .2s ease;}
        .logout-link:hover {background: #f3f4f6; box-shadow: 0 3px 10px rgba(0,0,0,.06);}        
    </style>

    <section class="verify-wrapper">
        <h2 class="verify-title">{{ __('Verify your email address') }}</h2>
        <p class="verify-info">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
        @if (session('status') == 'verification-link-sent')
            <div class="verify-status">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="verify-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="btn-primary">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <a href="{{ route('login') }}" class="logout-link" onclick="event.preventDefault(); logoutThenLogin();">
            {{ __('Iniciar sesión') }}
        </a>
        <script>
            function logoutThenLogin() {
                fetch('{{ route('logout') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).finally(() => {
                    window.location.href = '{{ route('login') }}';
                });
            }
        </script>
        </div>
    </section>
</x-guest-layout>
