<div class="card-body p-5 text-center">
    @if(Auth::user()->isAdmin())
        <h4 class="card-title"><small>No proposed exchanges.</small></h4>
        <p class="card-text text-muted">This user hasn't proposed any shift exchange so far.</p>
    @else
        <h4 class="card-title"><small>Proposed exchanges will appear here.</small></h4>
        <p class="card-text text-muted">When you propose a shift exchange in courses, you will be able to see it here.</p>
        <a href="{{ route('courses.index') }}" class="btn btn-info">Exchange shifts in courses</a>
    @endif
</div>
