<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExampleIndexRequestShouldSucceed()
    {
        $response = $this->get(route('example.index'));
        $response->assertStatus(200);
    }
}
