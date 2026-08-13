<a href="{{ route('projects.show', $project) }}" class="project-card reveal">
  <img src="{{ upload_url($project->cover_image, asset('img/placeholder.svg')) }}"
       alt="{{ $project->title }}" loading="lazy" />
  <div class="overlay">
    @if ($project->category)
      <span class="cat">{{ $project->category }}</span>
    @endif
    <h3>{{ $project->title }}</h3>
    <span class="meta">
      {{ collect([$project->location, $project->year])->filter()->implode(' • ') ?: $project->excerpt }}
    </span>
  </div>
</a>
