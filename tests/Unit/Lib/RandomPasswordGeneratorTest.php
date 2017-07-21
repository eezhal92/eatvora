<?php

namespace Tests\Unit\Lib;

use Tests\TestCase;
use App\Lib\RandomPasswordGenerator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RandomPasswordGeneratorTest extends TestCase
{
    /** @test */
    function random_password_are_8_characters_long()
    {
        $randomPasswordGenerator = new RandomPasswordGenerator;
        $password = $randomPasswordGenerator->generate();

        $this->assertTrue(strlen($password) === 8);
    }
}
