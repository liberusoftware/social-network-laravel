<div>
    <form wire:submit="save" class="space-y-4">
        <label>
            <span>Deployment mode</span>
            <select wire:model="deploymentMode">
                <option value="hosted">Hosted</option>
                <option value="self_hosted">Self hosted</option>
                <option value="federated">Federated</option>
            </select>
        </label>
        <button type="submit">Save social core settings</button>
    </form>
</div>
