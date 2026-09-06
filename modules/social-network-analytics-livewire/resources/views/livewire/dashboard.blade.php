<section aria-labelledby="analytics-heading">
    <h2 id="analytics-heading">Social network analytics</h2>
    <label for="analytics-metric">Metric</label>
    <select id="analytics-metric" wire:model.live="metric">
        <option value="growth">Growth</option>
        <option value="engagement">Engagement</option>
        <option value="retention">Retention</option>
        <option value="health">Health</option>
        <option value="moderation">Moderation</option>
        <option value="delivery">Delivery</option>
    </select>
    <div wire:loading aria-live="polite">Loading metrics…</div>
    @foreach ($this->snapshots() as $snapshot)
        <article><span>{{ $snapshot->metric }}</span>: <strong>{{ $snapshot->value }}</strong></article>
    @endforeach
</section>
