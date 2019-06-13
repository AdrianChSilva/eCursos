<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Requirement;
use Faker\Generator as Faker;

$factory->define(Requirement::class, function (Faker $faker) {
    return [
        'course_id' => \App\Course::all()->random()->id,
        'requirement' => $faker->sentence
    ];
});
