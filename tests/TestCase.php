<?php

namespace Tests;

use Exception;
use App\Exceptions\Handler;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Debug\ExceptionHandler;
use PHPUnit\Framework\Assert;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
  
    protected function setUp()
    {
        parent::setUp();

        // Let Laravel throw the exception by default
        // You can call withExceptionHandling later when You want assert response status code etc.
        $this->disableExceptionHandling();

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });

        Collection::macro('assertContains', function ($item) {
            Assert::assertTrue($this->contains($item));
        });

        Collection::macro('assertNotContains', function ($item) {
            Assert::assertFalse($this->contains($item));
        });
    }

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
