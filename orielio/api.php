<?php

namespace OrielIO;

/**
 * Class API - API helper for Oriel Service
 */
class API
{
    /**
     * Gets a resource from Oriel servers (ex: /domain)
     * @param string $resource
     * @return array|null
     */
    public static function get($resource)
    {
        global $oriel_settings;
        $settings = $oriel_settings;

        // No API Key set, do nothing
        if (!$settings->api_key)
            return null;

        $url = $settings->api_url . $resource;

        // cURL not installed
        if (!function_exists("curl_init"))
            return null;

        // Make request and fetch data
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "X-TI: 1",
                    "X-SDK: ". $settings->sdk_header,
                    "AUTHORIZATION: Bearer " . $settings->api_key
            )
        );

        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);
        $headers = Util::get_headers_from_request(substr($response, 0, $header_size));
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        if ($http_code == 200) {
            if (isset($headers['content-type']) && $headers['content-type'] == 'application/javascript') {
                return $body;
            }
            try {
                return json_decode($body, JSON_NUMERIC_CHECK);
            }
            catch(Exception $e) {
            }
        }

        return null;
    }

    public static function get_head_script()
    {
        global $oriel_cache, $oriel_settings;
        $settings = $oriel_settings;

        $script = $oriel_cache->get('head_script', '');
        if ($script) {
            return $script;
        }

        $result = API::get('/domain/loader');
        if ($result) {
            $oriel_cache->set('head_script', $result, $settings->head_script_cache_ttl);
            return $result;
        }

        return null;
    }
}
?>
