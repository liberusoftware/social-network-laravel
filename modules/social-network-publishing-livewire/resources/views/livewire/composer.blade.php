<div><form wire:submit="save" class="space-y-3">
    <label for="publication-kind">Type</label><select id="publication-kind" wire:model="kind"><option value="post">Post</option><option value="article">Article</option></select>
    <label for="publication-audience">Audience</label><select id="publication-audience" wire:model="audience"><option value="public">Public</option><option value="followers">Followers</option><option value="private">Private</option></select>
    <label for="publication-title">Title</label><input id="publication-title" wire:model="title" type="text">
    <label for="publication-body">Body</label><textarea id="publication-body" wire:model="body"></textarea>
    <button type="submit" wire:loading.attr="disabled">Save draft</button>
</form></div>
