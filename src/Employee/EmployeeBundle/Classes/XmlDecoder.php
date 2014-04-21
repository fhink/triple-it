<?php

namespace Employee\EmployeeBundle\Classes;

use Employee\EmployeeBundle\Interfaces\Decoder;

/**
 * Class XmlDecoder
 *
 * This class is responsible for decoding XML data.
 *
 * @package Employee\EmployeeBundle\Classes
 */
class XmlDecoder implements Decoder
{

    /**
     * Converts a XML string to an array
     *
     * @param string $data data to decode
     *
     * @return array|bool The XML data or false on failure
     */
    public function decode($data)
    {
        $xml = simplexml_load_string($data);
        $array = json_decode(json_encode((array)$xml), 1);
        return array($xml->getName() => $array);
    }
}