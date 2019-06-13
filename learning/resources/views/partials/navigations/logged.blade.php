<!-- Cuando ejecutas el comando php artisan make:auth crea las clases necesarias para un registro e inicio
 de sesión, como también genera la plantilla html necesaria. Aquí simplemente he cogido parte de esa plantilla.
 Es la parte de cerrar sesión ya que ésta es una funcionalidad común para todos los usuarios excepto para el
 invitado-->
<li class="nav-item dropdown">
    <a id="navbarDropdown"
       class="nav-link dropdown-toggle"
       href="#" role="button"
       data-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false"
    >
        {{ Auth::user()->name }} <span class="caret"></span>
    </a>

    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
           document.getElementById('logout-form').submit();"
        >
            {{ __("Cerrar sesión") }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>
