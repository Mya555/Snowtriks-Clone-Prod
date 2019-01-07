<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 09/12/2018
 * Time: 20:58
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TricksControllerTest extends WebTestCase
{

    public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful()
    {
        $client = self::createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return array(
            array('/'),
            array('/login'),
            array('/register'),
            // ...
        );
    }

}