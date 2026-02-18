<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Or for Bootstrap 5 specifically
        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\Mail::extend('brevo', function (array $config) {
            return new \App\Mail\Transports\BrevoTransport($config['key']);
        });

        // Share menus with frontend views
        view()->composer('*', function ($view) {
            if (Schema::hasTable('menus')) {
                $menus = \App\Models\Menu::with([
                    'items' => function ($q) {
                        $q->with('children')->whereNull('parent_id')->where('status', true)->orderBy('order');
                    }
                ])->get()->keyBy('slug');

                $view->with('headerMenu', $menus->get('header'));
                $view->with('footerMenu1', $menus->get('footer-quick-links-1'));
                $view->with('footerMenu2', $menus->get('footer-quick-links-2'));
            } else {
                $view->with([
                    'headerMenu' => null,
                    'footerMenu1' => null,
                    'footerMenu2' => null
                ]);
            }
        });
    }
}
