<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('setActiveClass')) {
    /**
     * Check if the url is active
     *
     * @param string $url
     * @param string $css_class
     * @return string
     */
    function setActiveClass($url, ?string $css_class = 'active')
    {
        return request()->is($url) ? $css_class : '';
    }
}

if (!function_exists('setActiveRouteClass')) {
    /**
     * Check if the route is active
     *
     * @param string $route
     * @param string $css_class
     * @return string
     */
    function setActiveRouteClass($route, $css_class = 'active')
    {
        return Route::currentRouteName() === $route ? $css_class : '';
    }
}