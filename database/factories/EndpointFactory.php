<?php
use App\Endpoint;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
$factory->define(Endpoint::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'cors_origin' => $faker->url,
        'subject' => $faker->sentence,
        'monthly_limit' => $faker->numberBetween(),
        'client_limit' => $faker->numberBetween(),
        'time_unit' => $faker->randomElement([
            'month', 'week', 'day', 'hour', 'minute'
        ]),
        'user_id' => factory(User::class)->create()
    ];
});
