<?php

namespace Employee\EmployeeBundle\Tests\Classes;

use Employee\EmployeeBundle\Classes\JsonDecoder;

class JsonDecoderTest extends \PHPUnit_Framework_TestCase
{
    public function testDecode()
    {
        $decoder = new JsonDecoder();
        $jsonString = '{"namedKey1":"some value","namedKey2":{"val1":"aa","val2":"bb"},"namedKey3":["valA","valB"]}';

        $expectedResult = array(
            "namedKey1" => "some value",
            "namedKey2" => array(
                "val1" => "aa", "val2" => "bb"
            ),
            "namedKey3" => array(
                "valA", "valB"
            )
        );
        $actualResult = $decoder->decode($jsonString);

        $this->assertEquals(
            $expectedResult,
            $actualResult,
            "The result should be " . print_r($expectedResult, true)
        );
    }
}
