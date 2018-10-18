<?php

namespace Tests\Unit;

use CommsExpress\CDN\File;
use PHPUnit\Framework\TestCase;
use CommsExpress\CDN\UploadResponse;

class UploadResponseTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->response = new UploadResponse();
    }

    /** @test */

    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(UploadResponse::class, $this->response);
    }

    /** @test */

    public function it_can_add_a_file(){
        $file =  new File();

        $this->response->addFile($file);

        $this->assertInstanceOf(File::class, $this->response->getFile());
    }

    /** @test */

    public function it_knows_it_was_successful()
    {
        $this->response->setToSuccessful();

        $this->assertTrue($this->response->success());
    }

    /** @test */

    public function it_knows_it_failed(){
        $this->response->setToFailed();

        $this->assertTrue($this->response->failed());
    }

    /** @test */

    public function it_can_set_an_error()
    {
        $this->response->setError(new \Exception('Test'));

        $this->assertFalse($this->response->success());
        $this->assertContains('A general error occurred', $this->response->getMessage());
    }
}
