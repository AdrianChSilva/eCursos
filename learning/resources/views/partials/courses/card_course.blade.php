<div class="card card-01">
    <img
        class="card-img-top"
        src="{{ $course->pathAttachment() }}"
        alt="{{ $course->name }}"
    />
    <div class="card-body">

        <h4 class="card-title">{{ $course->name }}</h4>
        <hr />
        <div class="row justify-content-center">
            @include('partials.courses.rating', ['rating' => $course->rating])
        </div>
        <hr />
        <span class="badge badge-danger badge-cat">{{ __($course->category->name) }}</span>
        <p class="card-text">
            {{-- Con Str::limit le ponemos un tope a la cantidad de caracteres que queramos mostrar--}}
            {{ Str::limit($course->description, 100) }}
        </p>
        <a
            href="{{ route('courses.detail', $course->slug) }}"
            class="btn btn-course btn-block"
        >
            {{ __("Más información") }}
        </a>
    </div>
</div>
