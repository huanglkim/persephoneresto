<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Menu;
use App\PesananDetail;

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
        // Menambahkan variabel $menus ke setiap tampilan
        View::composer('*', function ($view) {
            $menus = Menu::all();
            $jumlah_pesanan = PesananDetail::count();
            $view->with(compact('menus', 'jumlah_pesanan'));
        });
    }
}
