<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('sortable', function ($defaultColumn = 'created_at', $defaultDirection = 'desc') {
            $column = request('sort', $defaultColumn);
            $direction = request('direction', $defaultDirection);
            $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : $defaultDirection;
            
            if (preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                return $this->orderBy($column, $direction);
            }
            
            return $this->orderBy($defaultColumn, $defaultDirection);
        });
    }
}
