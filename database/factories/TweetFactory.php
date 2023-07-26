<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tweet;
use Faker\Generator as Faker;

$factory->define(Tweet::class, function (Faker $faker) {
    $user = App\User::orderByRaw('RAND()')->first();
    
    return [
        'user_id'=> $user->id,
        'body'=>$faker->sentence
    ];
});
