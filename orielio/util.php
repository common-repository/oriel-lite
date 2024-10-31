<?php

namespace OrielIO;

class Util
{

    public static function get_headers_from_request($header)
    {
        $headers = explode("\r\n", strtolower($header));
        $ret = [];
        foreach($headers as $line) {
            $line = explode(":", $line, 2);
            $ret[trim($line[0])] = trim($line[1]);
        }
        return $ret;
    }

}

?>
