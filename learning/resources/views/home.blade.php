@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', [
        "title" => __("Busca nuevos retos"),
        "icon" => "th"
    ])
@endsection


@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            @forelse($courses as $course)
                <div class="col-md-3">
                    @include('partials.courses.card_course')
                </div>
            @empty
                <div class="alert alert-dark">
                    {{ __("No existen cursos disponibles") }}
                </div>
            @endforelse
        </div>

        <div class="row justify-content-center">
            <!-- El método 'links()' lo que hace es pintar los enlaces de la paginacion utilizando bootstrap-->
            {{ $courses->links() }}
        </div>
    </div>
@endsection
