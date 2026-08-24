<div class="space-y-2">@foreach($this->entries() as $entry)<article>{{ $entry->item_type }}: {{ $entry->item_id }}</article>@endforeach</div>
