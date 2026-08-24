<x-filament-panels::page><div class="space-y-2">@foreach($this->reports() as $report)<div>{{ $report->reason }} — {{ $report->state }}</div>@endforeach</div></x-filament-panels::page>
