<?php

namespace OrielIO;

/**
 * Class Crypto Used to encode and rotate Oriel resource keys
 */
class Crypto
{

    public static function get_signup_url()
    {
        global $oriel_settings;
        $settings = $oriel_settings;
        $domain = $_SERVER['HTTP_HOST'];
        $data = array("domain"=> $domain,
                      "email" => get_option('admin_email'));
        
        $query = http_build_query($data);
        $url = $settings->activation_url."?".$query;
        return $url;
    }

}

?>
