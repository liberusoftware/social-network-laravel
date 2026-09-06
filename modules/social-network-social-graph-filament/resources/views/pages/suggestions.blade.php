<x-filament-panels::page>
    <div class="space-y-2" aria-label="Profile suggestions">
        @forelse ($suggestionRows as $suggestion)
            <div>{{ $suggestion['handle'] }}</div>
        @empty
            <p>No suggestions available.</p>
        @endforelse
    </div>
</x-filament-panels::page>
