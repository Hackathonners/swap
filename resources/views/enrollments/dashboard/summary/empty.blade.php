<div class="card-body p-5 text-center">
    @if(Auth::user()->isAdmin())
        <h4 class="card-title"><small>No enrolls.</small></h4>
        <p class="card-text text-muted">This user hasn't enrolled any course so far.</p>
    @else
        <h4 class="card-title"><small>Enrollments will appear here.</small></h4>
        <p class="card-text text-muted">When you enroll in courses, you will be able to exchange shifts.</p>
        <a href="{{ route('courses.index') }}" class="btn btn-info">Enroll in courses</a>
    @endif
</div>
