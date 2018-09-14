<?php
/**
 * Created by PhpStorm.
 * User: spencerc
 * Date: 14/09/2018
 * Time: 14:34
 */

namespace Tests\Unit;


use CommsExpress\CDN\CDNLibrary;
use CommsExpress\CDN\Exceptions\URLNotSet;
use GuzzleHttp\Client;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

class CDNLibraryTest extends TestCase
{
    /** @test */

    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(CDNLibrary::class, new CDNLibrary(new Client([
            'base_uri'  =>  'http://cdn.test'
        ])));
    }

    /** @test */

    public function it_requires_client_to_have_url_set()
    {
        $this->expectException(URLNotSet::class);

        $client = new Client();

        $library = new CDNLibrary($client);
    }
}