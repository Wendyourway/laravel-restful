<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\userTraits;

class RoleIndexTest extends TestCase
{
    use WithFaker, userTraits;

    public function testGetAllRolesWithNoUserShouldBeBeUnauthorized()
    {
        $response = $this->json("GET", route("role.index"));
        $response->assertStatus(401);
    }

    public function testGetAllRolesWithSubscriberShouldBeForbidden()
    {
        $token = $this->getTokenByRole("subscriber");
        $header = [
            "Authorization" => "Bearer $token"
        ];
        $response = $this->json("GET", route("role.index"), [], $header);
        $response->assertStatus(403);
    }

    public function testGetAllRolesWithModeratorShouldBeAllowed()
    {
        $token = $this->getTokenByRole("moderator");
        $header = [
            "Authorization" => "Bearer $token"
        ];
        $response = $this->json("GET", route("role.index"), [], $header);
        $response->assertStatus(200);
    }


    public function testGetAllRolesWithAdministratorShouldBeAllowed()
    {
        $token = $this->getTokenByRole("administrator");
        $header = [
            "Authorization" => "Bearer $token"
        ];
        $response = $this->json("GET", route("role.index"), [], $header);
        $response->assertStatus(200);
    }
}
