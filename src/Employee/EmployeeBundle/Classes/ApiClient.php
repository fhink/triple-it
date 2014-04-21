<?php
namespace Employee\EmployeeBundle\Classes;

/**
 * Class ApiClient
 *
 * This class is responsible for fetching API results.
 */
Class ApiClient
{
    /** base url of the API */
    const BASE_URL = "https://www.triple-it.nl/";

    const API_TYPE = "json";

    public static function getEmployees()
    {
        return self::decode(
            Http::get(self::BASE_URL . "jobs/assessment")
        );
    }

    public static function getImage($photoUrl)
    {
        return Http::get(self::BASE_URL . "resources/assessment/" . $photoUrl);
    }

    /**
     * Creates an array of the API result.
     *
     * @param string $result The API result to decode
     *
     * @return array Array of the API result
     */
    private static function decode($result)
    {
        switch (self::API_TYPE) {
            case "json":
                $decoder = new JsonDecoder();
                break;

            case "xml":
                $decoder = new XmlDecoder();
                break;

            default:
                throw new \Exception("API type not yet implemented");
        }

        $data = $decoder->decode($result);
        if ($data === FALSE) {
            throw new \Exception("Failed decoding API result");
        }
        return $data;
    }
}