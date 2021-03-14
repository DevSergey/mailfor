<?php
use App\Credential;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
$factory->define(Credential::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'host' => $faker->domainName,
        'port' => $faker->numberBetween(0, 65535),
        'from_address' => $faker->safeEmail,
        'from_name' => $faker->lastName,
        'encryption' => 'tls',
        'username' => $faker->userName,
        'password' => $faker->password,
        'user_id' => factory(App\User::class)
    ];
});
