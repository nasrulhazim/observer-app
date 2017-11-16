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
            $user->hashslug = Hash::make(time() . str_random(12));
        }
    }
}
```

5. Now you need to register your model, to the `OnCreatingObserver`. Open up your `app/Providers/AppServiceProvider.php` and add the following in `boot` method.

```php
\App\User::observe(\App\Observers\OnCreatingObserver::class);
```