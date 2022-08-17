<?php

namespace App\Providers;

use App\Services\Interfaces\PaymentService;
use App\Services\StripePaymentService;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(MessageBag::class, function () {
            return new MessageBag();
        });
        $this->app->singleton(StripeClient::class, function () {
            return new StripeClient(config('services.stripe.key'));
        });
        $this->app->singleton(PaymentService::class, function () {
            return new StripePaymentService(resolve(StripeClient::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
