<?php

/**
 * Default Oriel settings
 */

global $oriel_settings;

$oriel_settings = array(
    "debug" => false,
    "api_key" => "", // Your Website API Key
    "api_url"=> "https://gw.oriel.io/api", // Main API URL
    "head_script_cache_ttl" => 60, // Head script cache TTL (seconds)
    "sdk_header"=>"WPL",
    "activation_url" => "https://oriel.io/console/dashboard/integration/wordpress/activate",
);

/**
 * Get options from DB
 */

$options = get_option("oriel_lite_options");
if(!is_array($options))
    $options = array();


/**
 * Compute the final Oriel settings array
 */
$oriel_settings = array_merge($oriel_settings, $options);

if(function_exists('is_wpe') && is_wpe()) {
    $oriel_settings['is_wpe'] = true;
}

if(function_exists('cloudflare_init')) {
    $oriel_settings['uses_cloudflare'] = true;
}

if ($oriel_settings['is_wpe']) {
    $oriel_settings['sdk_header'] .= "/E";
}

$oriel_settings = new \ArrayObject($oriel_settings, \ArrayObject::ARRAY_AS_PROPS);

/**
 * Include $cache variable
 */
require_once(plugin_dir_path(__FILE__) . "cache.php");
?>
