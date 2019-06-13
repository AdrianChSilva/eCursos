<?php

namespace App\Http\Controllers;

use function foo\func;
use Illuminate\Http\Request;
use App\Course;
use App\Helpers\Helper;
use App\Http\Requests\CourseRequest;
use App\Mail\NewStudentInCourse;
use App\Review;

class CourseController extends Controller
{
    public function show (Course $course) {
        $course->load([
            'category' => function ($query) {
                $query->select('id', 'name');
            },
            'goals' => function ($query) {
                $query->select('id', 'course_id', 'goal');
            },
            'level' => function ($query) {
                $query->select('id', 'name');
            },
            'requirements' => function ($query) {
                $query->select('id', 'course_id', 'requirement');
            },
            'reviews.user',
            'teacher'
        ])->get();

        $related = $course->relatedCourses();
        return view('courses.detail', compact('course', 'related'));
    }

    public function addReview () {
        Review::create([
            "user_id" => auth()->id(),
            "course_id" => request('course_id'),
            "rating" => (int) request('rating_input'),
            "comment" => request('message')
        ]);
        return back()->with('message', ['success', __('Muchas gracias por tu valoración. Ésto nos ayuda
        a crecer')]);
    }

    /**
     *
     * Relacionamos las tablas de cursos y estudiantes (que es de muchos a muchos), con lo que quedará registrado
     * en BD como que el usuario está inscrito a ese curso. Además se le envía un correo electrónico
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inscribe (Course $course) {
        //relaciona al curso con el estudiante/usuario final
        $course->students()->attach(auth()->user()->student->id);

        //Si no consigo arreglar lo del email eliminaré esta linea
        \Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

        return back()->with('message', ['success', __("Te has inscrito correctamente al curso")]);
    }

    /**
     *
     * Con este metodo obtenemos los cursos a los que estamos inscritos
     */
    public function subscribed(){
        $courses = Course::whereHas('students', function($query) {
            $query->where('user_id', auth()->id());
        })->get();
        return view('courses.subscribed', compact('courses'));
    }

    public function create () {
        $course = new Course;
        $btnText = __("Enviar curso para revisión");
        return view('courses.form', compact('course', 'btnText'));
    }

    public function store (CourseRequest $course_request) {
        $picture = Helper::uploadFile('picture', 'courses');
        $course_request->merge(['picture' => $picture]);
        $course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
        $course_request->merge(['status' => Course::PENDING]);
        Course::create($course_request->input());
        return back()->with('message', ['success', __('Curso enviado correctamente, recibirá un correo con cualquier información')]);
    }

    public function edit ($slug) {
        //withCount es para evitar consultas duplicadas
        $course = Course::with(['requirements', 'goals'])->withCount(['requirements', 'goals'])
            ->whereSlug($slug)->first();
        $boton = __("Actualizar curso");
        return view('courses.form', compact('course', 'boton'));
    }

    public function update (CourseRequest $course_request, Course $course) {
        //comprobamos si en el objeto request existe un archivo 'picture'
        if($course_request->hasFile('picture')) {
            \Storage::delete('courses/' . $course->picture);
            $picture = Helper::uploadFile( "picture", 'courses');
            $course_request->merge(['picture' => $picture]);
        }
        $course->fill($course_request->input())->save();
        return back()->with('message', ['success', __('Curso actualizado')]);
    }

    public function destroy (Course $course) {
        try {
            $course->delete();
            return back()->with('message', ['success', __("El curso se eliminó satisfactoriamente")]);
        } catch (\Exception $exception) {
            return back()->with('message', ['danger', __("Ocurrió un error al eliminar el curso")]);
        }
    }
}
