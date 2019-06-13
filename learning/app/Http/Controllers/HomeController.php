<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *Eliminamos esta funcion ya que lo que hace es proteger el index de la página, y logicamente
     * no queremos eso
    public function __construct()
    {
        $this->middleware('auth');
    }
     *
     */

    /**
     * Show the application dashboard.
     *
     * Con esto lo que aharemos es acceder a todos los cursos (paginados) de la aplicación
     * siempre y cuando estén habilitados
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //este metodo nos permite coger el conteo de una relacion
        //La paginación está hecha con una consulta elocuent
        $courses = Course::withCount(['students'])
        ->with('category', 'teacher', 'reviews')
        ->where('status', Course::PUBLISHED)
        ->latest()
        ->paginate(12);


        return view('home', compact('courses'));
    }
}
