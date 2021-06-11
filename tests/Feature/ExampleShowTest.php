<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Example;
use Tests\TestCase;

class ExampleShowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExampleShowRequestShouldSucceed()
    {
        $example = Example::factory()->create();
        $response = $this->get(route('example.show', $example->id));
        $response->assertStatus(200);
    }
}
  