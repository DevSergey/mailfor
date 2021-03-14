<?php
use App\User;
use App\Validation;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
$factory->define(Validation::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'validation' => 'required|numeric|between:0,20',
        'user_id' => factory(User::class)->create()
    ];
});
