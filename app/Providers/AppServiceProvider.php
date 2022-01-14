<?php

namespace App\Providers;

use GuzzleHttp\Client;
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
        \Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $client = new Client();

            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                    ['form_params'=>
                        [
                            'secret' => Config('app_config.GOOGLE_RECAPTCHA_SECRET'),
                            'response' => $value
                        ]
                    ]
            );

            $body = json_decode((string)$response->getBody());
            //\Log::info($response->getBody());
            return $body->success;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
