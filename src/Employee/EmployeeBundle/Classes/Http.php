<?php
namespace Employee\EmployeeBundle\Classes;

/**
 * HTTP I/O abstraction.
 *
 * Responsible for executing CURL requests.
 */
class Http
{
    /** Name of this Web browser */
    const USERAGENT = "ApiClient";
    /** The maximum number of seconds to allow cURL functions to execute. */
    const CURL_TIMEOUT = 60;
    /** The maximum number of seconds to wait for an established session */
    const CURL_CONNECTTIMEOUT = 60;

    /**
     * Send an HTTP request.
     *
     * @param string $url URL to call
     * @param array|NULL $post All post-variables to add (all is auto escaped)
     * @param boolean|String $cookieJar File to write cookie data to
     * @param array $flags Additional cURL-flags
     *
     * @throws \Exception When failing to interact with the server/cURL
     * @return String The CURL response
     */
    public static function get($url, array $post = NULL, $cookieJar = FALSE, array $flags = array())
    {
        /** @var bool $optSet variable that will be used to see if a curl option is successfully set */
        $optSet = TRUE;

        $ch = self::curlInit($url);

        // set curl options
        {
            if ($post !== NULL) {
                $optSet &= curl_setopt($ch, CURLOPT_POST, count($post));
                $optSet &= curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            }
            $optSet &= curl_setopt($ch, CURLOPT_USERAGENT, self::USERAGENT);
            $optSet &= curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            if ($cookieJar !== FALSE) {
                if (strlen(file_get_contents($cookieJar)) == 0) {
                    $optSet &= curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
                } else {
                    $optSet &= curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
                }
            }
            $optSet &= curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CURL_CONNECTTIMEOUT);
            $optSet &= curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
            foreach ($flags as $key => $value) {
                $optSet &= curl_setopt($ch, $key, $value);
            }

            if ($optSet === FALSE) {
                throw new \Exception("One or more cURL option-flags failed");
            }
        }

        return self::curlExec($ch, $url);
    }

    /**
     * Wrapper to gte a cURL handler with some error checking.
     *
     * @param string $url The URL
     *
     * @return resource cURL handle
     * @throws \Exception
     */
    private static function curlInit($url)
    {
        if (! function_exists("curl_init")) {
            throw new \Exception("cURL-library not available, please install/enable.");
        }

        $ch = curl_init($url);
        if ($ch === FALSE) {
            throw new \Exception("Error calling curl_init");
        }

        return $ch;
    }

    /**
     * Wrapper for curl_exec with some error handling.
     *
     * @param resource $ch cURL handle
     * @param string $url the URL called
     *
     * @return mixed the CURL result
     * @throws \Exception
     */
    private static function curlExec($ch, $url)
    {
        $result = curl_exec($ch);
        if ($result === FALSE) {
            throw new \Exception(
                sprintf("Error calling curl_exec #%d: %s (%s)", curl_errno($ch), curl_error($ch), $url)
            );
        }

        curl_close($ch);
        unset($ch);

        return $result;
    }
}
