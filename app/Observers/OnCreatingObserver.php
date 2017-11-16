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
