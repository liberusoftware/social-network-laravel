<x-filament-panels::page><div class="space-y-2">@foreach($this->assets() as $asset)<div>{{ $asset->type }} — {{ $asset->state }} — {{ $asset->path }}</div>@endforeach</div></x-filament-panels::page>
