<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Course
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $teacher_id
 * @property int $category_id
 * @property int $level_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string|null $picture
 * @property string $status
 * @property int $previous_approved
 * @property int $previous_rejected
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePreviousApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePreviousRejected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereUpdatedAt($value)
 */
class Course extends Model
{
    protected $fillable = ['teacher_id', 'name', 'description', 'picture', 'level_id', 'category_id', 'status'];

    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    use SoftDeletes;

    protected $withCount = ['reviews', 'students'];

    public static function boot () {
        parent::boot();

        static::saving(function(Course $course) {
            if( ! \App::runningInConsole() ) {
                $course->slug = str_slug($course->name, "-");
            }
        });

        static::saved(function (Course $course) {
            //esta comprobacion la hacemos para evitar errores cuando carguemos seeds desde la consola
            if ( ! \App::runningInConsole()) {
                if ( request('requirements')) {
                    foreach (request('requirements') as $key => $requirement_input) {
                        if ($requirement_input) {
                            Requirement::updateOrCreate(['id' => request('requirement_id'. $key)], [
                                'course_id' => $course->id,
                                'requirement' => $requirement_input
                            ]);
                        }
                    }
                }

                if(request('goals')) {
                    foreach(request('goals') as $key => $goal_input) {
                        if( $goal_input) {
                            Goal::updateOrCreate(['id' => request('goal_id'.$key)], [
                                'course_id' => $course->id,
                                'goal' => $goal_input
                            ]);
                        }
                    }
                }
            }
        });
    }
    public function pathAttachment(){
        return "/images/courses/" . $this->picture;
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }

    //Realizamos las relaciones de los modelos de las distintas tablas
    public function category(){
        return $this->belongsTo(Category::class)->select('id', 'name');
    }

    public function goals(){
        return $this->hasMany(Goal::class)->select('id', 'course_id', 'goal'); //es necesario llamar a las foreign keys
    }

    public function level(){
        return $this->belongsTo(Level::class)->select('id', 'name');
    }

    public function reviews() {
        return $this->hasMany(Review::class)->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at');
    }

    public function requirements () {
        return $this->hasMany(Requirement::class)->select('id', 'course_id', 'requirement');
    }

    //un curso puede tener muchos alumnos y un alumno puede estar en muchos cursos. (en el modelo de estudiante se ha tenido que ahcer la misma relacion pero a la inversa
    public function students () {
        return $this->belongsToMany(Student::class);
    }

    public function teacher () {
        return $this->belongsTo(Teacher::class);
    }

    /**
     *
     * Recogemos la puntuacion media desde la tabla reviews.
     * Es neceario que empiece por 'get' y acabe con 'Attribute'.
     * @return puntuación
     */
    public function getRatingAttribute () {
        return $this->reviews->avg('rating');
    }

    /**
     *
     * Con esto ya tendriamos los cursos relacionados
     * @return Course[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */

    public function relatedCourses (){
        return Course::with('reviews')->whereCategoryId($this->category->id)
            ->where('id', '!=' , $this->id)
            ->latest()
            ->limit(4)
            ->get();
    }


}
