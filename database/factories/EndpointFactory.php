<?php
use App\Credential;
use App\Endpoint;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
$factory->define(Endpoint::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    return [
        'name' => $faker->sentence,
        'cors_origin' => $faker->url,
        'subject' => $faker->sentence,
        'monthly_limit' => $faker->numberBetween(),
        'client_limit' => $faker->numberBetween(),
        'time_unit' => $faker->randomElement([
            'month', 'week', 'day', 'hour', 'minute'
        ]),
        'user_id' => $user,
        'credential_id' => factory(Credential::class)->create([
            'user_id' => $user
        ])->id
    ];
});
