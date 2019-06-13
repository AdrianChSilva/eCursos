<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Review;
use Faker\Generator as Faker;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'course_id' => \App\Course::all()->random()->id,
        'rating' => $faker->randomFloat(2, 1, 5)

    ];
});
