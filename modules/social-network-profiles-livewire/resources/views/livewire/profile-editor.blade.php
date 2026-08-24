<div>
    <form wire:submit="save" class="space-y-4">
        <input wire:model="handle" type="text" aria-label="Handle">
        <textarea wire:model="bio" aria-label="Bio"></textarea>
        <select wire:model="visibility" aria-label="Visibility">
            <option value="public">Public</option>
            <option value="followers">Followers</option>
            <option value="private">Private</option>
        </select>
        <input wire:model="avatarPath" type="text" aria-label="Avatar path">
        <button type="submit">Save profile</button>
    </form>
</div>
