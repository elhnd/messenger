<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagePostControllerTest extends WebTestCase
{
   public function testCreate()
   {
        $client = static::createClient();

        $uploadFile = new UploadedFile(
            __DIR__.'/../fixtures/ponka-relaxed.png',
            'ponka-relaxed.png'
        );

        $client->request('POST', '/api/images', [], [
            'file' => $uploadFile
        ]);

        $this->assertResponseIsSuccessful();

        $transport = self::getContainer()->get('messenger.transport.async_priority_high');

        $this->assertCount(1, $transport->get());
   } 
}