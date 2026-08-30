<div>
    <form wire:submit="save" class="space-y-4" aria-label="Social Core settings">
        <label for="social-core-deployment-mode">
            <span>Deployment mode</span>
            <select id="social-core-deployment-mode" wire:model="deploymentMode">
                <option value="hosted">Hosted</option>
                <option value="self_hosted">Self hosted</option>
                <option value="federated">Federated</option>
            </select>
        </label>
        @foreach ([
            'networkSettings' => 'Network settings',
            'terminology' => 'Terminology',
            'featurePolicy' => 'Feature policy',
            'sharedIds' => 'Shared IDs',
        ] as $field => $label)
            <label for="social-core-{{ $field }}">
                <span>{{ $label }}</span>
                <textarea id="social-core-{{ $field }}" wire:model="{{ $field }}" aria-describedby="{{ $field }}-error"></textarea>
                @error($field)<span id="{{ $field }}-error" role="alert">{{ $message }}</span>@enderror
            </label>
        @endforeach
        <button type="submit" wire:loading.attr="disabled">Save social core settings</button>
    </form>
</div>
