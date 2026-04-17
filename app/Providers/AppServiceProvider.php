<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;

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
        //
        Paginator::useTailwind();

        if (config('custom.force_https')) {
            $this->app['request']->server->set('HTTPS', 'on');
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Verification Email For Registration
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new \App\Mail\VerificationMail($notifiable, $url))
                ->to($notifiable->email)
                ->subject('[No Reply] Verifikasi Akun');
        });

        //  Verification Email For Forget Password Email
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            return (new \App\Mail\ForgetPassword(
                $notifiable,
                url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->email,
                ], false))
            ))
                ->to($notifiable->email)
                ->subject('[No Reply] Lupa Password');
        });
    }
}
