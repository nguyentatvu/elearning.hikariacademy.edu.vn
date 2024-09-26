<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

/**
 * Generate random string
 *
 * @param int $length
 * @return string
 */
function makeRandomPassword(int $length = 6)
{
    $alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';

    if ($length < 1) {
        throw new InvalidArgumentException('Invalid input');
    }
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

/**
 * Create slug
 *
 * @param string $name
 * @param string $field
 * @return string
 */
function createSlug($model, $name, $field = 'slug')
{
    $slug = Str::slug($name);
    $existingSlugCount = $model::where($field, 'like', $slug . '%')->count();

    if ($existingSlugCount > 0) {
        $slug .= '-' . ($existingSlugCount);
    }

    return $slug;
}

/**
 * This is a common method to send emails based on the requirement
 * The template is the key for template which is available in db
 * The data part contains the key=>value pairs
 * That would be replaced in the extracted content from db
 * @param  [type] $template [description]
 * @param  [type] $data     [description]
 * @return [type]           [description]
 */
function sendEmail($template, $data)
{
    return (new App\EmailTemplate())->sendEmail($template, $data);
}

/**
 * Get IP address and get information from it
 *
 * @param string $ip
 * @param string $purpose
 * @param bool $deep_detect
 * @return array
 */
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode,
                        "ip"             => $_SERVER["REMOTE_ADDR"]
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

/**
 * Format currency VND
 *
 * @param int $type
 * @return string
 */
function formatCurrencyVND($amount, int $type = 1)
{
    if ($type == 1) {
        return number_format($amount, 0, ',', '.') . 'đ';
    } else if ($type == 2) {
        return number_format($amount, 0, '.', ' ') . ' VND';
    }
}

/**
 * Format number
 *
 * @param mixed $number
 * @param string $seperator
 * @return string
 */
function formatNumber($number, $seperator = ',')
{
    return number_format($number, 0, '', $seperator);
}
