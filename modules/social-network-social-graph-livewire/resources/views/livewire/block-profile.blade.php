<div>
    <form wire:submit="block" aria-label="Block profile">
        <label for="social-graph-block-profile-id">Profile ID</label>
        <input id="social-graph-block-profile-id" wire:model="profileId" type="text">
        <button type="submit" wire:loading.attr="disabled">Block</button>
        <button type="button" wire:click="unblock" wire:loading.attr="disabled">Unblock</button>
    </form>
</div>
