<x-filament-panels::page>
    <div class="space-y-2" aria-label="Blocked profiles">
        @forelse ($blockRows as $block)
            <div>{{ $block['target'] }}</div>
        @empty
            <p>No blocked profiles.</p>
        @endforelse
    </div>
</x-filament-panels::page>
