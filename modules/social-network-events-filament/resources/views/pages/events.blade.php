<x-filament-panels::page><div class="space-y-2">@foreach($this->events() as $event)<div>{{ $event->title }} — {{ $event->starts_at }}</div>@endforeach</div></x-filament-panels::page>
