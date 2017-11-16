<?php

$user = \App\User::create([
    'name'     => str_random(8),
    'email'    => str_random(8) . '@' . str_random(4) . '.com',
    'password' => bcrypt('password'),
]);
