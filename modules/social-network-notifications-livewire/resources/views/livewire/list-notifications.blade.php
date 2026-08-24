<div class="space-y-2" wire:loading.class="opacity-50" aria-live="polite">
    @forelse($this->notifications() as $notification)
        <div class="flex items-center justify-between">
            <span>{{ $notification->kind }} — {{ $notification->state }}</span>
            @if($notification->state !== 'read')
                <button type="button" wire:click="read('{{ $notification->id }}')" wire:loading.attr="disabled">Mark read</button>
            @endif
        </div>
    @empty
        <p>No notifications.</p>
    @endforelse
</div>
