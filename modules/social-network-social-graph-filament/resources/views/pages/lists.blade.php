<x-filament-panels::page>
    <div class="space-y-2" aria-label="Profile lists">
        @forelse ($listRows as $list)
            <div>{{ $list['name'] }} ({{ $list['profile_count'] }})</div>
        @empty
            <p>No lists.</p>
        @endforelse
    </div>
</x-filament-panels::page>
