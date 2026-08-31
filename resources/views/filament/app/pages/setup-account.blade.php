<x-filament-panels::page>
    <div class="mx-auto w-full max-w-4xl space-y-6">
        <div class="rounded-2xl bg-primary-50 p-6 ring-1 ring-primary-100 dark:bg-primary-950/30 dark:ring-primary-900">
            <p class="text-sm font-medium text-primary-700 dark:text-primary-300">A quick start for your workspace</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight">Welcome, {{ auth()->user()->name }}</h2>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-300">Complete the essentials now, then explore the network. You can revisit profile, team, security, and connections from the grouped menus at any time.</p>
        </div>

        <form wire:submit="completeSetup" class="space-y-6">
            {{ $this->form }}

            @if ($providers !== [])
                <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900" aria-labelledby="connections-heading">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 id="connections-heading" class="font-semibold">Optional sign-in connections</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Connect a provider for faster sign-in. This uses the existing OAuth flow and does not expose provider secrets.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium dark:bg-gray-800">{{ count($connectedProviders) }}/{{ count($providers) }} connected</span>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($providers as $provider)
                            <a href="{{ route('oauth.redirect', ['provider' => $provider]) }}" class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 text-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-gray-700 dark:hover:bg-primary-950/30" wire:key="provider-{{ $provider }}">
                                <span class="font-medium">{{ $this->providerLabel($provider) }}</span>
                                @if (in_array($provider, $connectedProviders, true))
                                    <span class="text-xs font-medium text-success-600">Connected</span>
                                @else
                                    <span class="text-xs text-gray-500">Connect</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900" aria-labelledby="api-heading">
                <h3 id="api-heading" class="font-semibold">Need API access?</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create and revoke personal API tokens from your account security area. Team members should use their own least-privilege token.</p>
                @if (Route::has('api-tokens.index'))
                    <a href="{{ route('api-tokens.index') }}" class="mt-3 inline-flex text-sm font-medium text-primary-600 hover:underline">Manage API tokens</a>
                @endif
            </section>

            <x-filament::button type="submit" icon="heroicon-o-check">Finish setup</x-filament::button>
        </form>
    </div>
</x-filament-panels::page>
