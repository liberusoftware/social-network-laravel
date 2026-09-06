<x-filament-panels::page>
    <div class="mx-auto w-full max-w-4xl space-y-6">
        <div class="rounded-2xl bg-primary-50 p-6 ring-1 ring-primary-100 dark:bg-primary-950/30 dark:ring-primary-900">
            <p class="text-sm font-medium text-primary-700 dark:text-primary-300">A quick start for your workspace</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight">Welcome, {{ auth()->user()->name }}</h2>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-300">Complete the essentials now, then explore the network. You can revisit profile, team, security, and connections from the grouped menus at any time.</p>
        </div>

        <form wire:submit="completeSetup" class="space-y-6">
            {{ $this->form }}

            @if ($providerStatus !== [])
                <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900" aria-labelledby="connections-heading">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 id="connections-heading" class="font-semibold">Connections checklist</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Connect a provider for faster sign-in. OAuth client credentials are configured by the project administrator and are never entered here.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium dark:bg-gray-800">{{ count(array_filter($providerStatus, fn (array $provider): bool => $provider['connected'])) }}/{{ count($providerStatus) }} connected</span>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($providerStatus as $provider)
                            @if ($provider['configured'] && Route::has('oauth.redirect'))
                                <a href="{{ route('oauth.redirect', ['provider' => $provider['id']]) }}" class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 text-sm transition hover:border-primary-500 hover:bg-primary-50 dark:border-gray-700 dark:hover:bg-primary-950/30" wire:key="provider-{{ $provider['id'] }}">
                                    <span class="font-medium">{{ $provider['label'] }}</span>
                                    @if ($provider['connected'])
                                        <span class="text-xs font-medium text-success-600">Connected</span>
                                    @else
                                        <span class="text-xs text-gray-500">Connect</span>
                                    @endif
                                </a>
                            @else
                                <div class="flex items-center justify-between rounded-lg border border-dashed border-gray-200 px-4 py-3 text-sm dark:border-gray-700" wire:key="provider-{{ $provider['id'] }}">
                                    <span class="font-medium">{{ $provider['label'] }}</span>
                                    <span class="text-xs text-gray-500">Admin setup needed</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        You can skip connections and return to them from Account &amp; Team later.
                    </p>
                </section>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900" aria-labelledby="api-heading">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 id="api-heading" class="font-semibold">API access</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Personal API tokens are optional. Create one only when you need to connect your own tools, and use the shortest practical lifetime.</p>
                    </div>
                    <span class="rounded-full bg-success-50 px-2.5 py-1 text-xs font-medium text-success-700 dark:bg-success-950/30 dark:text-success-300">Available</span>
                </div>
                @if (Route::has('api-tokens.index'))
                    <a href="{{ route('api-tokens.index') }}" class="mt-3 inline-flex text-sm font-medium text-primary-600 hover:underline">Manage personal API tokens</a>
                @else
                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">No token management screen is enabled for this installation. API access remains available to configured integrations.</p>
                @endif
            </section>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-gray-500 dark:text-gray-400">You can update these details later from the grouped navigation.</p>
                <x-filament::button type="submit" icon="heroicon-o-check">Finish setup</x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
