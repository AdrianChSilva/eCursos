<div class="col-2">
    {{-- Con esto estamos diciendo que si el usuario está registrado (@auth) mostrará un boton
     y si no está registrado mostrará otro--}}
    @auth
        @can('opt_for_course', $course)
            @can('subscribe', \App\Course::class)
                <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('subscriptions.plans')}}">
                    <i class="fa fa-bolt"></i> {{ __("Subscribirme") }}
                </a>
            @else
                @can('inscribe', $course)
                    <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.inscribe', ['slug' => $course->slug]) }}">
                        <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                    </a>
                @else
                    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                        <i class="fa fa-bolt"></i> {{ __("Inscrito") }}
                    </a>
                @endcan
            @endcan
        @else
            <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                <i class="fa fa-user"></i> {{ __("Soy autor") }}
            </a>
        @endcan
    @else
        <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('login') }}">
            <i class="fa fa-user"></i> {{ __("Acceder") }}
        </a>
        <p>{{__("Inicia sesión o regístrate para acceder a este curso")}}</p>
    @endauth
</div>
