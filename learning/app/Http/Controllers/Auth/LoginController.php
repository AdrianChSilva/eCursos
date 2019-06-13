<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Student;
use App\UserSocialAccount;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     *
     * Con este metodo pedimos que cuando hacemos log out nos deje en la pagina de logout y no en la principal
     * @param Request $request
     */
    public function logout(Request $request)
    {
        auth()->logout();
        session()->flush();//con esto forzamos el borrado de las variables de todas las sesiones
        return redirect('login');
    }

    /**
     *
     * Ésto lo que hace es un redirect al driver o plataforma con el que nos estamos
     * registrando, que puede ser facebook o github para pedir los permisos si es que el usuario
     * todavia no los ha dado.
     * Si pulsamos en iniciar sesión con laguna red social, llamamos primero a éste métoodo
     * @param string $driver
     * @return mixed
     */
    public function redirectToProvider (string $driver){
        return Socialite::driver($driver)->redirect();
    }

    /**
     *
     * Una vez la red social ha dado el OK llamamos a esta función y nos va a redirigir
     * el usuario a la url que nos ha devuelto la red social
     * @param string $driver
     */
    public function handleProviderCallback(string $driver){
        /**
         * Con este condicional eliminamos una excepción que nos da facebook a la hora de darle al
         * boton de cancelar. Si el request no tiene el codigo o es denegado, nos redirige al login
         */
        if ( ! request()->has('code') || request()->has('denied')){
            //le mandamos un mensaje al usuario para que sepa qué está pasando
            session()->flash('message', ['danger', __('Inicio de sesión cancelado')]);
            return redirect('login');
        }
        $socialUser = Socialite::driver($driver)->user();

        $user = null;
        $success = true;
        $email = $socialUser->email;//todas las plataformas devuelven el email excepto twitter
        //aquí empleamos uno de los métodos mágicos
        $check = User::whereEmail($email)->first();
        /**
         * Tenemos que comprobar si el registro $check existe. Si existe, no queremos dar de alta al usuario
         * en su caso le iniciaremos sesión. Si no existe, le daremos de alta y luego iniciaremos sesión
         */
        if ($check){
            $user = $check;
        }else{
            \DB::beginTransaction();

            /**
             * hacemos este try por lo siguiente: Imaginemos que estamos registrando el usuario y éste se
             * crea correctamente, pero luego hacemos el registro de la cuenta social y éste falla, y luego creamos
             * el registro del estudiante y éste también falla.
             * Entonces vamos a tener un usuario que no va a estar relacionado con un estudiante ni con
             * una cuenta social. Cuando haces un registro de una cuenta social, el usuario no tiene
             * que ingresar su password y entonces esto va a fallar, por lo que obligaría al usuario
             * a recuperar su password
             *
             * */
            try {
                $user = User::create([
                    "name" => $socialUser->name,
                    "email" => $email
                ]);
                UserSocialAccount::create([
                    "user_id" => $user->id,
                    "provider" => $driver,
                    "provider_uid" => $socialUser->id
                ]);
                Student::create([
                    "user_id" => $user->id
                ]);
            } catch (\Exception $exception) {
                $success = $exception->getMessage();
                \DB::rollBack(); //con esto deshacemos tudo lo que se haya podido hacer
            }
        }
        if ($success === true){
            //si tudo sale bien guardaremos la informacion en base de datos
            \DB::commit();
            auth()->loginUsingId($user->id);
            return redirect(route('home'));
        }
        session()->flash('message', ['danger', $success]);
        return redirect('login');

        //con esto imprimimos información en pantalla una vez facebook o github ha redirigido al usuario
       //dd($socialUser);
    }
}
