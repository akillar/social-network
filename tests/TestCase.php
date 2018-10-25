<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    /**
     *  Function to assert if a Class uses a Trait.
     *  * You can add it in your TestCase.php file
     *  and use it from any file inside /tests.
     */
    public function assertClassUsesTrait($trait, $class) {

        $this->assertArrayHasKey(

            $trait,
            class_uses($class),
            "{$class} must use {$trait} trait"

        );
    
    }
}
