<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Builder::macro('search', function ($field, $string) {
        //     return $string ? $this->where($field, 'like', '%'.$string.'%') : $this;
        // });

        // Builder::macro('toCsv', function () {
        //     $results = $this->get();

        //     if ($results->empty()) return;

        //     $title = implode(',', array_keys((array) $results->first()->getAttributes()));
        //     $values = $results->map(function ($result) {
        //         return implode(',', collect($result->getAttributes())->map(function ($thing) {
        //             return '"'.$thing.'"';
        //         })->toArray());
        //     });
        //     $values->prepend($titles);
        //     return $values->implode("\n");
        // });
    }
}
