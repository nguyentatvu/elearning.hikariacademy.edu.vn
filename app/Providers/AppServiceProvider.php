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
                            $path = strpos($image, 'banners/') === 0 ? $image : 'banners/' . $image;
                            return 'storage/' . $path;
                        });
                    } else {
                        $path = strpos($banner->image, 'banners/') === 0 ? $banner->image : 'banners/' . $banner->image;
                        $banner->image = asset('storage/' . $path);
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
