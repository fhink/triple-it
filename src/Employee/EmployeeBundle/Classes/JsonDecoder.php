<?php

namespace Employee\EmployeeBundle\Classes;

use Employee\EmployeeBundle\Interfaces\Decoder;

/**
 * Class JsonDecoder
 *
 * This class is responsible for decoding json data.
 *
 * @package Employee\EmployeeBundle\Classes
 */
class JsonDecoder implements Decoder
{

    /**
     * Converts a JSON string to an array
     *
     * @param string $data data to decode
     *
     * @return array|bool The Json data or false on failure
     */
    public function decode($data)
    {
        return json_decode($data, TRUE);
    }
}