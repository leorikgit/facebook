<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'user_id' => factory(User::class)
    ];
});
