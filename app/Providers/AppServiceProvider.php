<?php

namespace App\Providers;

use App\Banner;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

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
            $banners = Cache::remember('site_banners', 60, function () {
                return $this->getBanners();
            });

            $view->with('banners', $banners);
        });
    }

    /**
     * Get processed banners
     *
     * @return \Illuminate\Support\Collection
     */
    private function getBanners()
    {
        $banners = Banner::whereNotNull('image')->get()->keyBy('position');

        $banners->each(function ($banner) {
            if ($banner->image) {
                if ($banner->display_type == 'multi_image') {
                    $banner->image = collect(json_decode($banner->image))->map(function ($image) {
                        return asset($image);
                    });
                } else {
                    $banner->image = asset($banner->image);
                }
            }
        });

        return $banners;
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