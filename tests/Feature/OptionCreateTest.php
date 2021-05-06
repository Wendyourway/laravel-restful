<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\userTraits;
use Illuminate\Foundation\Testing\WithFaker;

class OptionCreateTest extends TestCase
{
    use WithFaker, userTraits;
    public function testCreateOptionWithoutASessionShouldBeUnauthorized()
    {

        $response = $this->json("POST", "/api/option", [
            "name" => "option1",
            "value" => "Some Option Value",
        ]);

        $response->assertStatus(401);
    }

    public function testCreateOptionAsAnAdministratorShouldBeAllowed()
    {
        $token = $this->getTokenByRole("administrator");
        $header = [
            "Authorization" => "Bearer $token",
        ];
        $response = $this->json("POST", "/api/option", [
            "name" => "Option2",
            "value" => "Some Option",
        ], $header);

        $response->assertStatus(200);
    }

    public function testCreateOptionAsModeratorShouldBeForbidden()
    {
        $token = $this->getTokenByRole("moderator");
        $header = [
            "Authorization" => "Bearer $token",
        ];
        $response = $this->json("POST", "/api/option", [
            "name" => "Option3",
            "value" => "Some Option",
        ], $header);
        $response->assertStatus(403);
    }

    public function testCreateOptionAsSubscriberShouldBeForbidden()
    {
        $token = $this->getTokenByRole("subscriber");
        $header = [
            "Authorization" => "Bearer $token",
        ];

        $response = $this->json("POST", "/api/option", [
            "name" => "Option4",
            "value" => "Some Option",
        ], $header);
        $response->assertStatus(403);
    }
}
