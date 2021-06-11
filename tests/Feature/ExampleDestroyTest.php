<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Example;
class ExampleDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExampleDestroyRequestShouldSucceed()
    {
        $example = Example::factory()->create();
        $response = $this->delete(route('example.destroy', $example->id));
        $response->assertStatus(200);
    }
}
