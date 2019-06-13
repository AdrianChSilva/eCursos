<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Goal;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        'course_id' => \App\Course::all()->random()->id,
        'goal' => $faker->sentence

    ];
});
