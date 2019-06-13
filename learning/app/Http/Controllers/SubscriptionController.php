<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    //implemento un middleware en el constructor proque así me ahorro de hacer dos controladores
    public function __construct()
    {
        $this->middleware(function($request, $next){

            //Comprobamos si el usuario está suscrito a algún plan. Si lo está, no dejamos que entre
            if ( auth()->user()->subscribed('main') ) {
                return redirect('/')
                    ->with('message', ['warning', __("Ya tienes una suscripción")]);
            }

            return $next($request);
        })->only(['plans', 'processSubscription']);
    }

    /**
     *
     * Procesamos las suscripciones
     */
    public function processSubscription(){
        $token = request('stripeToken');
        try {
            if ( \request()->has('coupon')) {
                \request()->user()->newSubscription('main', \request('type'))
                    ->withCoupon(\request('coupon'))->create($token);
            } else {
                \request()->user()->newSubscription('main', \request('type'))
                    ->create($token);
            }
            return redirect(route('subscriptions.admin'))
                ->with('message', ['success', __("Te has suscrito correctamente")]);
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
            return back()->with('message', ['danger', $error]);
        }
    }

    public function admin () {
        $subscriptions = auth()->user()->subscriptions;
        return view('subscriptions.admin', compact('subscriptions'));
    }
    /**
     *
     * Mostramoss los planes disponibles que tengamos en la plataforma
     */
    public function plans(){
        return view('subscriptions.plans');
    }


    public function resume () {
        $subscription = \request()->user()->subscription(\request('plan'));
        if ($subscription->cancelled() && $subscription->onGracePeriod()) { //en periodo de gracia
            \request()->user()->subscription(\request('plan'))->resume();
            return back()->with('message', ['success', __("Suscripción reanudada correctamente")]);
        }
        return back();
    }

    public function cancel () {
        auth()->user()->subscription(\request('plan'))->cancel();
        return back()->with('message', ['success', __("Suscripción cancelada correctamente")]);
    }
}
