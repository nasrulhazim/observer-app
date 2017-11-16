1. Create a new Laravel Application `laravel new observer-app`

2. Go into `observer-app` directory.

3. Create a directory called `Observers` in `app` directory and create a new file called `OnCreatingObserver.php` in `app/Observers`

4. `OnCreatingObserver` class will capture Eloquent event on `creating` a new record for a given model.

```php
<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class OnCreatingObserver
{
    public function creating(Model $model)
    {
        if (Schema::hasColumn($model->getTable(), 'hashslug') && is_null($model->hashslug)) {
            // create hashslug based on timestamp random string
            $model->hashslug = Hash::make(time() . str_random(12));
        }
    }
}
```

5. Now you need to register your model, to the `OnCreatingObserver`. Open up your `app/Providers/AppServiceProvider.php` and add the following in `boot` method.

```php
\App\User::observe(\App\Observers\OnCreatingObserver::class);
```

6. Lastly, update your user's migration file to add in new column called `hashslug`.

```php
$table->string('hashslug');
```

7. Run `php artisan migrate`

# Tinker

I always love to run and test things using Tinker, rather than test my backend codes on front-end - web.

1. Now create a folder in your application `tinkers` and add in a new file called `create_user.php`.

2. Open up `create_user.php` and add the following codes to create a new user.

```php
<?php

$user = \App\User::create([
    'name'     => str_random(8),
    'email'    => str_random(8) . '@' . str_random(4) . '.com',
    'password' => bcrypt('password'),
]);
```

3. Now run `php artisan tinker tinkers/create_user.php`. You should have something like the following:

```bash
➜ php artisan tinker tinkers/create_user.php
Psy Shell v0.8.15 (PHP 7.1.11 — cli) by Justin Hileman
>>> $user
=> App\User {#734
     name: "cJDDcHFM",
     email: "h017ep4N@PWO7.com",
     hashslug: "$2y$10$LwYV6GjUZHuDDjG88sWTqOpN.2yrjrORThzYtwMimgHqQ.hGQ6oDy",
     updated_at: "2017-11-16 22:40:56",
     created_at: "2017-11-16 22:40:56",
     id: 1,
   }
```

Notice that we have our `hashslug` inserted - but we don't even assign the value in the tinker (`create_user.php`)! 

So, that's how a simple user case on observe events emiited by Eloquent.

# Back to the Future

## Problem Statement

So you know, you have the an observer and it's awesome. Easy for you. But imagine you have more than 10 models need to observe the `OnCreatingObserver`. It's a tedious job to manage and your `AppServiceProvider`'s `boot` method will be bloated.

### Issues

Three issues here:

1. Bloated Codes
2. Unmanageable Codes
3. Single Observer, can be Observer by many models.

### Solutions

### Bloated Codes

For the issue no. 1, the strategy, which I apply to my recent works - refer to how Laravel provide us to register custom commands in their `app/Console/Kernel.php`.

Same thing I will apply for the Bloated Codes.