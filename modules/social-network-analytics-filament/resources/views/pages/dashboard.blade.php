<x-filament-panels::page>
    <div class="space-y-2" aria-label="Social network analytics">
        @foreach ($this->snapshots() as $snapshot)
            <div>{{ $snapshot->metric }}: {{ $snapshot->value }}</div>
        @endforeach
    </div>
</x-filament-panels::page>
