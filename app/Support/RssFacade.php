<?php

namespace App\Support;


use Illuminate\Support\Facades\Cache;

trait RssFacade
{
    /**
     * Read XML from stream
     *
     * @return bool|\SimpleXMLElement
     */
    public function readXML()
    {

        /*
         * Find out if we can use cache, and data is cached
         */
        if (config('settings.cache.enabled')) {
            $cache = Cache::get('xml');
            if ($cache) {
                return simplexml_load_string($cache);
            }
        }

        // Get system compatible data reader
        $reader = $this->getReader();

        // get rss feed url
        $url = config('settings.rss_url');

        // if no rss feed url specified, return no data
        if (!$url) {
            return false;
        }

        // get data form feed
        $xml = $this->getFeedData($reader, $url);

        // if cache is enabled - cache received data for specified time
        if (config('settings.cache.enabled', 0)) {
            Cache::put("xml", $xml, config('settings.cache.ttl', 60));
        }

        return simplexml_load_string($xml);
    }

    /**
     * @return bool|string
     */
    public function getReader()
    {
        // look for suitable data retrievieng option
        if (function_exists('curl_version')) {
            $reader = "curl";
        } elseif (ini_get('allow_url_fopen')) {
            $reader = "get";
        } elseif (function_exists('fsockopen')) {
            $reader = "fsockopen";
        }

        return $reader??false;
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function getByCurl($url)
    {
        // init curl
        $curl = curl_init($url);

        // set curl to return data
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    public function getBySocket($url)
    {
        $xml = "";
        /*
         * split url in url parts
         * 0 - protocol
         * 1 - host
         * 2 - url
         */
        preg_match(config('app.url_parser'), $url, $urlParts);

        /*
         * only proceed if host and url have been found
         */
        if ($urlParts[2] && $urlParts[3]) {

            /*
             * set protocol and port
             */
            $host = ($urlParts[1] == "s" ? "ssl://" : "") . $urlParts[2];
            $port = $urlParts[1] == "s" ? 443 : 80;

            // open connection
            $fp = fsockopen($host, $port, $errno, $errstr, 30);

            if (!$fp) {
                // if connection can not be made, return no data
                return false;
            } else {
                // format raw http data
                $out = "GET /{$urlParts[3]} HTTP/1.1\r\n";
                $out .= "Host: {$urlParts[2]}\r\n";
                $out .= "Connection: Close\r\n\r\n";
                fwrite($fp, $out);
                $xmlData = "";

                // read data from stream
                while (!feof($fp)) {
                    $xmlData .= fgets($fp, 128);
                }
                fclose($fp);

                // remove headers/footers and remove tabs/newlines
                $xmlData = strstr($xmlData, "<?xml");
                $xmlData = strstr($xmlData, "rss>", true);
                $xmlData = preg_replace("/[\\x00-\\x19]+/", "", $xmlData);

                $xml = $xmlData . "rss>";
            }
        }

        return $xml;
    }

    /**
     * @param $reader
     * @param $url
     *
     * @return bool|mixed|string
     */
    public function getFeedData($reader, $url) {
        switch ($reader) {
            case "get":
                $xml = file_get_contents($url);
                break;

            case "curl":
                $xml = $this->getByCurl($url);
                break;

            case "fsockopen":
                $xml = $this->getBySocket($url);
                break;
        }
        return $xml??false;
    }
}