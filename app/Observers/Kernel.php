<?php

namespace App\Observers;

/**
 *
 */
class Kernel
{
    /**
     * Array of model-observer
     * @var array
     */
    protected $observers = [
        // FQCN of Model => FQCN of Observer
        \App\User::class => \App\Observers\OnCreatingObserver::class,
    ];

    /**
     * Make this class
     * @return \App\Observers\Kernel
     */
    public static function make()
    {
        return (new self);
    }

    /**
     * Register observers
     * @return void
     */
    public function observes()
    {
        if (count($this->observers) > 0) {
            foreach ($this->observers as $model => $observer) {
                if (class_exists($model) && class_exists($observer)) {
                    $model::observe($observer);
                }
            }
        }
    }
}
