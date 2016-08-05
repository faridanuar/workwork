<?php

namespace App\Providers;

use AlgoliaSearch\Client;

use App\Contracts\Search;

use App\Services\AlgoliaSearch;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Braintree_Configuration::environment(env('BRAINTREE_ENV'));
        \Braintree_Configuration::merchantId(env('BRAINTREE_MERCHANT_ID'));
        \Braintree_Configuration::publicKey(env('BRAINTREE_PUBLIC_KEY'));
        \Braintree_Configuration::privateKey(env('BRAINTREE_PRIVATE_KEY'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Search::class, function() {

            $config = config('services.algolia');

            return new AlgoliaSearch(

                new Client($config['app_id'], $config['api_key'])
            );
        });
    }
}
