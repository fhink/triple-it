<?php

namespace Employee\EmployeeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Employee overview")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("View details")')->count());
    }
}