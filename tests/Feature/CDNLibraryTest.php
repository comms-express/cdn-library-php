<?php

namespace Tests\Feature;

use CommsExpress\CDN\CDNLibrary;
use CommsExpress\CDN\File;
use CommsExpress\CDN\UploadResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use Psr\Http\Message\RequestInterface;

class CDNLibraryTest extends \PHPUnit\Framework\TestCase
{
    /** @test */

    public function uploads_a_file()
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200,[],$this->prepareResponse())
        ]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);

        $client = new \GuzzleHttp\Client([
            'handler'   =>  $handler,
            'base_uri'  =>  'http://cdn.test'
        ]);

        $library = new CDNLibrary($client);

        $response = $library->upload($this->getFile());

        $this->assertInstanceOf(UploadResponse::class, $response);
        $this->assertTrue($response->success());
        $this->assertInstanceOf(File::class, $response->getFile());

        $file = $response->getFile();
        $this->assertEquals($file->getID(), 1);
        $this->assertEquals($file->getURL(), 'http://cdn.test/file.png');
    }

    /** @test */

    public function can_get_file_info()
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200,[],$this->prepareResponse())
        ]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $handler->push(function(callable $handler){
            return function(RequestInterface $request, array $options) use($handler){
                $request = $request->withHeader('Accept', 'application/json');
                return $handler($request, $options);
            };
        });

        $client = new \GuzzleHttp\Client([
            'handler'   =>  $handler,
            'base_uri'  =>  'http://cdn.test'
        ]);

        $library = new CDNLibrary($client);
        $file_id = 1;

        $file = $library->getFile($file_id);

        $this->assertInstanceOf(File::class, $file);
        $this->assertEquals(1, $file->getID());
        $this->assertEquals('http://cdn.test/file.png', $file->getURL());
    }

    /** @test */

    public function provides_error_if_upload_fails()
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(500,[],$this->prepareResponse())
        ]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $handler->push(function(callable $handler){
            return function(RequestInterface $request, array $options) use($handler){
                $request = $request->withHeader('Accept', 'application/json');
                return $handler($request, $options);
            };
        });

        $client = new \GuzzleHttp\Client([
            'handler'   =>  $handler,
            'base_uri'  =>  'http://cdn.test'
        ]);

        $library = new CDNLibrary($client);

        $response = $library->upload($this->getFile());

        $this->assertFalse($response->success());
        $this->assertNotEmpty($response->getMessage());
    }

    private function getFile(){
        return fopen('./tests/example.png','r');
    }

    private function prepareResponse(){
        return json_encode([
            'data'    =>  [
                'id'    =>  1,
                'attributes'    =>  [
                    'url'   =>  'http://cdn.test/file.png'
                ]
            ]
        ]);
    }
}
