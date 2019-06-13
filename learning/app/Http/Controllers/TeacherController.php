<?php

namespace App\Http\Controllers;
use App\Course;
use App\Mail\MessageToStudent;
use App\Student;
use App\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

    public function sendMessageToStudent () {
        //requerimos la informacion que hemos enviado via ajax
        $info = \request('info');
        $data = [];
        //con esto parseamos (o enviamos) la informacion de $info hacia $data. Guarda la info ajax en un array
        parse_str($info, $data);
        //buscamos a un usuario por su ID. findOrFail lo que hace es que si no encuentra el usuario, aborta la peticion
        $user = User::findOrFail($data['user_id']);
        try {
            \Mail::to($user)->send(new MessageToStudent( auth()->user()->name, $data['message']));
            $success = true;
        } catch (\Exception $exception) {
            $success = false;
        }
        //este 'res' es lo que recoge el script de /profile/index para saber si envia el correo al usuario
        return response()->json(['res' => $success]);
    }
    public function students(){
        $students = Student::with('user', 'courses.reviews')
            ->whereHas('courses', function ($q) {
                $q->where('teacher_id', auth()->user()->teacher->id)->select( 'teacher_id', 'name')->withTrashed(); //el campo 'id' me daba error de ser ambiguo
                //con withTrashed() lo que hacemos es hacer referencia al borrado logico que he implementado en la tabla cursos
            })->get();

        $actions = 'students.datatables.actions';
        return \DataTables::of($students)->addColumn('actions', $actions)->rawColumns(['actions', 'courses_formatted'])->make(true);
    }

    public function courses () {
        $courses = Course::withCount(['students'])->with('category', 'reviews')
            ->whereTeacherId(auth()->user()->teacher->id)->paginate(5);
        return view('teachers.courses', compact('courses'));
    }
}
