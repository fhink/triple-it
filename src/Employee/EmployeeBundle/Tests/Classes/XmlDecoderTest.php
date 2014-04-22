<?php

namespace Employee\EmployeeBundle\Tests\Classes;

use Employee\EmployeeBundle\Classes\XmlDecoder;

class XmlDecoderTest extends \PHPUnit_Framework_TestCase
{
    public function testDecodeNotSame()
    {
        $decoder = new XmlDecoder();
        $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<employee>
	<name>Franklin Hink</name>
	<email>frhink@gmail.com</email>
	<phone>0652553442</phone>
</employee>
XML;

        $expectedResult = array(
            "namedKey1" => "some value",
            "namedKey2" => array(
                "val1" => "aa", "val2" => "bb"
            ),
            "namedKey3" => array(
                "valA", "valB"
            )
        );
        $actualResult = $decoder->decode($xmlString);

        $this->assertNotSame(
            $expectedResult,
            $actualResult,
            "The result should be " . print_r($expectedResult, TRUE)
        );
    }

    public function testDecodeSame() {
        $decoder = new XmlDecoder();
        $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<employee>
	<name>Franklin Hink</name>
	<email>frhink@gmail.com</email>
	<phone>0652553442</phone>
</employee>
XML;

        $expectedResult = array(
            "employee" => array(
                "name" => "Franklin Hink",
                "email" => "frhink@gmail.com",
                "phone" => "0652553442"
            )
        );
        $actualResult = $decoder->decode($xmlString);

        $this->assertSame(
            $expectedResult,
            $actualResult,
            "The result should be " . print_r($expectedResult, TRUE)
        );
    }
}
