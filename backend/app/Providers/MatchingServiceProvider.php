<?php

namespace App\Providers;

use App\Domain\Matching\Scoring\CompositeScorer;
use Illuminate\Support\ServiceProvider;

class MatchingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CompositeScorer::class, function () {
            $scorers = collect(config('matching.scorers'))
                ->map(fn ($class) => app($class))
                ->toArray();

            return new CompositeScorer($scorers);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
