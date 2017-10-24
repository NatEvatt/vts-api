<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Parser;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->input('token', $request->bearerToken());
            if ($token) {
                $parsed = (new Parser())->parse((string) $token);
                $name = $parsed->getClaim('name');
                if ($parsed) {
                    $theUser = new User();
                    $theUser->user_id = $parsed->getClaim('sub');
                    $theUser->name = $parsed->getClaim('name');
                    $theUser->email = $parsed->getClaim('email');
                    return $theUser;
                }
                return false;
            }
        });




        // $this->app['auth']->viaRequest('api', function ($request) {
        //     // get token from request input or authorization header
        //     $token = $request->input('token', $request->bearerToken());
        //     if ($token) {
        //         $parsed = app(TokenWrangler::class)->getContext($token);
        //         if ($parsed) {
        //             return new User($parsed);
        //         }
        //     }
        //     return false;
        // });
    }
}
