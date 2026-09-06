<div>
    <form wire:submit="follow" class="space-y-2" aria-label="Follow or friend profile">
        <label for="social-graph-follow-profile-id">Profile ID</label>
        <input id="social-graph-follow-profile-id" wire:model="profileId" type="text">
        <button type="submit" wire:loading.attr="disabled">Follow</button>
        <button type="button" wire:click="friend" wire:loading.attr="disabled">Request friendship</button>
    </form>
</div>
