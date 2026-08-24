<x-filament-panels::page><div class="space-y-2">@foreach($this->communities() as $community)<div>{{ $community->name }} — {{ $community->visibility }}</div>@endforeach</div></x-filament-panels::page>
