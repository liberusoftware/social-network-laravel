<x-filament-panels::page>
    <div class="space-y-2" aria-label="Relationships">
        @forelse ($relationshipRows as $relationship)
            <div>{{ $relationship['type'] }}: {{ $relationship['target'] }} ({{ $relationship['status'] }})</div>
        @empty
            <p>No relationships.</p>
        @endforelse
    </div>
</x-filament-panels::page>
