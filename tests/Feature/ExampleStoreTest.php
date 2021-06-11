<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Example;
use Tests\TestCase;

class ExampleStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExampleStoreRequestShouldSucceed()
    {
        $example = Example::factory()->make();
        $response = $this->post(route('example.store'), [
            'param1' => $example->param1,
            'param2' => $example->param2,
        ]);
        $response->assertStatus(200);
    }
}
