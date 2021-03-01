<?php

namespace Tests;

use ReflectionObject;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use \Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Class TestCase
 *
 * Used for implementing common method across test cases
 */
class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Make sure root facade is set for http client
         */
        Http::swap(new Factory());
    }

    public function getPrivateProperty($object, string $property)
    {
        $r = new ReflectionObject($object);
        $p = $r->getProperty($property);
        $p->setAccessible(true);

        return $p->getValue($object);
    }
}
