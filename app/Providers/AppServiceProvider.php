<?php

namespace App\Providers;

use App\Banner;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();

        View::composer('*', function ($view) {
            $banners = Banner::whereNotNull('image')->get()->keyBy('position');

            $banners->each(function ($banner) {
                if ($banner->image) {
                    if ($banner->display_type == 'multi_image') {
                        $banner->image = collect(json_decode($banner->image))->map(function ($image) {
                            return asset($image); // Image path is already relative to public directory
                        });
                    } else {
                        $banner->image = asset($banner->image); // Image path is already relative to public directory
                    }
                }
            });

            $view->with('banners', $banners);
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
