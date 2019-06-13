<div class="row mb-4">
    <div class="col-md-12">
        <div class="card" style="background-image: url('{{ url('/images/jumbotronDetail.jpg') }}');">
            <div class="text-white text-center d-flex align-items-center py-5 px-4 my-5">
                <div class="col-5">
                    <img class="img-fluid" src="{{ $course->pathAttachment() }}" />
                </div>

                <div class="col-5 text-left">
                    <h1>{{ __("Curso") }}: {{ $course->name }}</h1>
                    <h4>{{ __("Profesor/a") }}: {{ $course->teacher->user->name }}</h4>
                    <h5>{{ __("Categoría") }}: {{ __($course->category->name) }}</h5>
                    <h5>{{ __("Fecha de publicación") }}: {{ $course->created_at->format('d/m/Y') }}</h5>
                    <h5>{{ __("Fecha de actualización") }}: {{ $course->updated_at->format('d/m/Y') }}</h5>
                    <h6>{{ __("Usuarios inscritos") }}: {{ $course->students_count }}</h6>
                    <h6>{{ __("Valoraciones") }}: {{ $course->reviews_count }}</h6>
                    @include('partials.courses.rating', ['rating' => $course->rating])

                </div>

                @include('partials.courses.action_button')

            </div>
        </div>
    </div>
</div>
