<?php

namespace App\Policies;

use App\User;
use App\Course;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     *
     * comprueba que en nuestra relacion de muchos a muchos de cursos y estudiantes si el estudiante es el
     * usuario actual
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public function inscribe (User $user, Course $course) {
        return ! $course->students->contains($user->student->id);
    }

    /**
     *
     * Comprobamos qze el usuario no tenga el rol de admin y que no esté registrado
     * @param User $user
     * @return bool
     */
    public function subscribe (User $user) {
        return $user->role_id !== Role::ADMIN && ! $user->subscribed('main');
    }
    /**
     *
     * Éste metodo lo que hace es decir si un usuario puede optar o no a un curso
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public function opt_for_course (User $user, Course $course) {
        return ! $user->teacher || $user->teacher->id !== $course->teacher_id;
    }

    /**
     *
     * Comprobamos si el usuario puede annadir una resenna, es decir, si no la ha hecho todavia
     * pues podrá hacerla
     * @param User $user
     * @param Course $course
     * @return bool
     */
    public function review (User $user, Course $course) {
        return ! $course->reviews->contains('user_id', $user->id);
    }


}
