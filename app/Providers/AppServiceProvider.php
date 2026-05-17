<?php

namespace App\Providers;

use App\Domain\Services\ContactScoreCalculatorService;
use App\Domain\Services\ScoreRules\EmailScoreRule;
use App\Domain\Services\ScoreRules\NameScoreRule;
use App\Domain\Services\ScoreRules\PhoneScoreRule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ContactScoreCalculatorService::class,
            function () {
                return new ContactScoreCalculatorService([
                    new EmailScoreRule(),
                    new NameScoreRule(),
                    new PhoneScoreRule(),
                ]);
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
