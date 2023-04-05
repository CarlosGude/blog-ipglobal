<?php


namespace App\Tests\Api\Validation;

use App\Tests\Api\AbstractTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserValidationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        AbstractTest::setUp();
    }

    public function testEmailError():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('POST','http://localhost/api/user',[
            'json' => [
                'name' => 'test',
                'email' => 'error_email',
                'password' => 'password1'
            ]
        ]);
        $body = $response->toArray(false);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertArrayHasKey('email',$body);
        $this->assertEquals('This value is not a valid email address.',$body['email']);
    }

    public function testPasswordError():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('POST','http://localhost/api/user',[
            'json' => [
                'name' => 'test',
                'email' => 'email@test.com',
                'password' => 'error_pass'
            ]
        ]);
        $body = $response->toArray(false);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertArrayHasKey('password',$body);
        $this->assertEquals('This value is not valid.',$body['password']);
    }

    public function testEmailExistError():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('POST','http://localhost/api/user',[
            'json' => [
                'name' => 'test',
                'email' => 'carlos@gmail.com',
                'password' => 'password1'
            ]
        ]);
        $body = $response->toArray(false);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertArrayHasKey('email',$body);
        $this->assertEquals('This email is already in use.',$body['email']);
    }

    public function testNameNotSent():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('POST','http://localhost/api/user',[
            'json' => [
                'email' => 'test@gmail.com',
                'password' => 'password1'
            ]
        ]);
        $body = $response->toArray(false);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertArrayHasKey('name',$body);
        $this->assertEquals('This value should not be null.',$body['name']);
    }

    public function testEmailNotSent():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('POST','http://localhost/api/user',[
            'json' => [
                'name' => 'Test',
                'password' => 'password1'
            ]
        ]);
        $body = $response->toArray(false);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertArrayHasKey('email',$body);
        $this->assertEquals('This value should not be null.',$body['email']);
    }

    public function testPasswordNotSent():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('POST','http://localhost/api/user',[
            'json' => [
                'name' => 'Test',
                'email' => 'carlos@gmail.com',
            ]
        ]);
        $body = $response->toArray(false);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertArrayHasKey('password',$body);
        $this->assertEquals('This value should not be null.',$body['password']);
    }

    public function testListUserNotExist():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('GET','http://localhost/api/user/8');

        $this->assertEquals(404,$response->getStatusCode());
    }

    public function testDeleteNotFound():void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var HttpClientInterface $httpClient */
        $httpClient = $container->get(HttpClientInterface::class);
        $response = $httpClient->request('DELETE','http://localhost/api/user/1');

        $this->assertEquals(204,$response->getStatusCode());
        $response = $httpClient->request('GET','http://localhost/api/user/1');

        $this->assertEquals(404,$response->getStatusCode());

    }

    public function testUpdatePassword(): void
    {
        $this->assertTrue(false);
    }
}