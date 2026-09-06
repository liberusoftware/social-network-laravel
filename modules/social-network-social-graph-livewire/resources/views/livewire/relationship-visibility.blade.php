<div>
    <form wire:submit="save" aria-label="Update relationship visibility">
        <label for="social-graph-relationship-id">Relationship ID</label>
        <input id="social-graph-relationship-id" wire:model="relationshipId" type="text">
        <label for="social-graph-relationship-visibility">Visibility</label>
        <select id="social-graph-relationship-visibility" wire:model="visibility">
            <option value="private">Private</option><option value="followers">Followers</option><option value="public">Public</option>
        </select>
        <button type="submit" wire:loading.attr="disabled">Save visibility</button>
    </form>
</div>
