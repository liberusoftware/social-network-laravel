<div>
    <form wire:submit="create" aria-label="Create profile list">
        <label for="social-graph-list-name">Name</label>
        <input id="social-graph-list-name" wire:model="name" type="text">
        <label for="social-graph-list-visibility">Visibility</label>
        <select id="social-graph-list-visibility" wire:model="visibility">
            <option value="private">Private</option><option value="followers">Followers</option><option value="public">Public</option>
        </select>
        <button type="submit" wire:loading.attr="disabled">Create list</button>
    </form>
    <ul aria-label="Profile lists">
        @forelse ($ownedLists as $list)
            <li>{{ $list['name'] }}</li>
        @empty
            <li>No lists yet.</li>
        @endforelse
    </ul>
</div>
