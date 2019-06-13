<?php

namespace App\Http\Middleware;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    //Este es el emtido que se ejecuta por defecto
    public function handle($request, Closure $next)
    {
        /**
         *
         * Establecimos una variable session "applocale" y aquí comprobamos si existe. Y si existe vamos
         * a nuestro archivo de configuración de idiomas y seleccionamos el que se haya pedido desde la vista
         */
        if (session('applocale')) {
            $configLanguage = config('language')[session('applocale')];
            setlocale(LC_TIME, $configLanguage[1] . '.utf8');
            /**
             *
             * Estas dos clases son necesarias. Con la claser Carbon y 'setLocale' estamos diciendo que, cuando
             * cambiemos al idioma espaniol (por ejemplo), queremos la fecha y la hora formateada a ese idioma.
             * Con App y 'setLocale' estamos estableciendo el idioma de la aplicación
             */
            Carbon::setLocale(session('applocale'));
            App::setLocale(session('applocale'));
        } else {
            /**
             *
             * Si la variable sessión 'applocale' no existiese, la aplicación escogerá el idioma que tenga por
             * defecto
             */
            session()->put('applocale', config('app.fallback_locale'));
            setlocale(LC_TIME, 'es_ES.utf8');
            Carbon::setLocale(session('applocale'));
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}
