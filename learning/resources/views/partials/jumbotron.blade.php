{{-- Este jumbotron es comun para todas las secciones excepto para el detalle del curso
 el cual tiene su propio jumbotron--}}
<div class="row">
    <div class="col-md-12">
        <div class="card pt-5" style="background-image: url('{{ url('/images/jumbotron.jpg') }}')">
            <h1 class="card-title pt-3 mb-5 text-center text-white">
                <i class="fa fa-{{ $icon }}"></i>
                <strong>{{ __($title) }}</strong>
            </h1>
        </div>
    </div>
</div>
