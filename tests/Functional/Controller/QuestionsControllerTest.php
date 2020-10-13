<?php


namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionsControllerTest extends WebTestCase
{
    public function testGetAction()
    {
        static::createClient()->request('GET', '/questions');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testPostActionValidData()
    {
        /* Todo move it to parent class */
        $json = file_get_contents(__DIR__ . '/../../fixtures/questionValidPostData.json');
        $client = static::createClient();
        $client->request('POST', '/question', [], [], [], $json);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonStringEqualsJsonString($client->getResponse()->getContent(), $json);
    }

    public function testPostActionInvalidData()
    {
        $json = file_get_contents(__DIR__ . '/../../fixtures/questionInvalidPostData.json');
        $client = static::createClient();
        $client->request('POST', '/question', [], [], [], $json);

        $this->assertResponseStatusCodeSame(400);
    }
}