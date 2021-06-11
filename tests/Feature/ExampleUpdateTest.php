<?php

namespace Tests\Feature;

use Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Example;
use Tests\TestCase;

class ExampleUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExampleUpdateRequestShouldSucceed()
    {
        $example = Example::factory()->create();
        $response = $this->put(route('example.update', $example->id), [
            'param1' => 100,
            'param2' => 'Hello World',
        ]);
        $response->assertStatus(200);
    }
}
