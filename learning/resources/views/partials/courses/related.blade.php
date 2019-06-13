{{-- Mostramos cursos similares --}}
<div class="col-12 pt-0 mt-4">
    <h2 class="text-muted">
        {{ __("También te puede interesar") }}
    </h2>
    <hr />
</div>
<div class="container-fluid">
    <div class="row">
        @forelse($related as $relatedCourse)
            <div class="col-md-6 listing-block">
                <div class="media">
                    <div class="fav-box">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                    </div>
                    <a href="{{ route('courses.detail', $relatedCourse->slug) }}">
                        <img
                            class="d-flex align-self-start"
                            src="/images/courses/{{ $relatedCourse->picture }}"
                            alt="{{ $relatedCourse->name }}"
                        />
                    </a>
                    <div class="media-body pl-3">
                        <div class="price">
                            <small>{{ $relatedCourse->name }}</small>
                        </div>
                        <div class="stats">
                            @include('partials.courses.rating', ['rating' => $relatedCourse->custom_rating])
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-dark">
                {{ __("No existe ningún curso relacionado") }}
            </div>
        @endforelse
    </div>
</div>
