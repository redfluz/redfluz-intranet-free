@php
    use App\Models\System\Setting;

    $backgroundImage = auth()->user()?->background_image;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-palette="{{ auth()->user()?->theme ?? 'pinkpanther' }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Redfluz') }}</title>

    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/sass/themes.scss', 'resources/js/app.js'])

    <style>
        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="bg-2 app-background {{ $backgroundImage ? 'has-custom-background' : '' }}"
    @if ($backgroundImage) style="--app-background-image: url('{{ asset('storage/' . $backgroundImage) }}')" @endif>
    {{-- Navigation lives in the topbar (see layouts.nav). There is no
 sidebar rail and no show/hide state to restore. --}}
    <div class="app-wrapper d-flex flex-column w-100 min-vh-100" id="app">
        <!-- Topbar (contains main navigation + user menu) -->
        <header class="topbar-wrapper border-bottom bg-1 flex-shrink-0">
            @include('layouts.topbar')
        </header>

        <!-- Main Content Area -->
        <main class="content-col p-6 md:p-7 flex-grow-1">
            @yield('content')
        </main>
    </div>

    @auth
        {{-- Echo / Realtime Messages & Sound --}}
        <script>
            window.messageConversationUrl = {{ Illuminate\Support\Js::from(route('personnel.messages.show', ['user' => '__USER_NAME__'])) }};

            window.playNotificationSound = function () {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                    gain.gain.setValueAtTime(0.1, ctx.currentTime);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.15);
                } catch (error) {
                    console.log('Audio playback context error:', error);
                }
            };

            document.addEventListener('DOMContentLoaded', () => {
                if (typeof window.Echo === 'undefined') {
                    return;
                }

                window.Echo.private('user.{{ Auth::id() }}').listen('.MessageSent', (event) => {
                    const incomingMessage = event.message;
                    if (!incomingMessage) {
                        return;
                    }

                    // The active chat page handles its own incoming messages.
                    if (
                        window.activeChatPartnerId &&
                        String(window.activeChatPartnerId) === String(incomingMessage.sender_id)
                    ) {
                        if (typeof window.appendIncomingMessage === 'function') {
                            window.appendIncomingMessage(incomingMessage);
                        }
                        return;
                    }

                    // Otherwise point the badge at the sender's conversation,
                    // bump the unread count and ping.
                    const messagesLink = document.getElementById('global-messages-link');
                    const senderName = incomingMessage.sender?.name;
                    if (messagesLink && senderName) {
                        messagesLink.href = window.messageConversationUrl.replace(
                            '__USER_NAME__',
                            encodeURIComponent(senderName),
                        );
                    }

                    const badge = document.getElementById('global-unread-badge');
                    if (badge) {
                        badge.textContent = String((parseInt(badge.textContent, 10) || 0) + 1);
                        badge.classList.remove('d-none');
                    }
                    window.playNotificationSound();
                });

                window.Echo.private('user.{{ Auth::id() }}').listen('.MessageDeleted', (event) => {
                    // "For me" only affects the tab of the user who deleted;
                    // "for everyone" affects both participants.
                    if (event.mode !== 'everyone' && String(event.userId) !== '{{ Auth::id() }}') {
                        return;
                    }

                    document.getElementById(`message-item-${event.messageId}`)?.remove();
                });
            });
        </script>
    @endauth

    <script>
        let bootstrapInvalidTabActivated = false;

        document.addEventListener(
            'invalid',
            (event) => {
                if (bootstrapInvalidTabActivated || !(event.target instanceof Element)) return;

                const tabPane = event.target.closest('.tab-pane');
                if (!tabPane || tabPane.classList.contains('active')) return;

                const tab = Array.from(document.querySelectorAll('[data-bs-toggle="tab"]')).find(
                    (item) => item.getAttribute('data-bs-target') === `#${tabPane.id}`,
                );
                if (tab) {
                    bootstrapInvalidTabActivated = true;
                    tab.click();
                    setTimeout(() => (bootstrapInvalidTabActivated = false), 0);
                }
            },
            true,
        );
    </script>

    @stack('scripts')
    @yield('script')
</body>

</html>
