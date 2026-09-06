<div aria-label="Profile suggestions">
    @forelse ($profiles as $profile)
        <div>{{ $profile['handle'] }}</div>
    @empty
        <p>No suggestions available.</p>
    @endforelse
</div>
