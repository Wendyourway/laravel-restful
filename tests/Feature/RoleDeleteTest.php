<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\userTraits;

class RoleDeleteTest extends TestCase
{
    use WithFaker, userTraits;

    public function testDeleteRoleWithNoUserShouldBeUnauthorized()
    {
        $data = [
            "name" => "TestRoleDelete",
            "slug" => "testroleDelete",
            "permissions" => [
                "test.index" => true,
                "test.get" => true,
                "test.update" => true,
                "test.store" => true,
                "test.delete" => true,
            ]
        ];
        $role = $this->createRole($data);
        $response = $this->json("DELETE", "/api/role/" . $role->slug, $data);
        $response->assertStatus(401);
    }

    public function testDeleteRoleAsSubscriberShouldBeForbidden()
    {
        $data = [
            "name" => "TestRoleDeleteAsSubscriber",
            "slug" => "testroleDeleteAsSubscriber",
            "permissions" => [
                "test.index" => true,
                "test.get" => true,
                "test.update" => true,
                "test.store" => true,
                "test.delete" => true,
            ]
        ];
        $role = $this->createRole($data);
        $token = $this->getTokenByRole("subscriber");
        $header = [
            "Authorization" => "Bearer $token"
        ];
        $response = $this->json("PUT", "/api/role/" . $role->slug, $data, $header);
        $response->assertStatus(403);
    }

    public function testDeleteRoleAsModeratorShouldBeForbidden()
    {
        $data = [
            "name" => "TestRoleDeleteAsModerator",
            "slug" => "testroleDeleteAsModerator",
            "permissions" => [
                "test.index" => true,
                "test.get" => true,
                "test.update" => true,
                "test.store" => true,
                "test.delete" => true,
            ]
        ];
        $role = $this->createRole($data);
        $token = $this->getTokenByRole("moderator");
        $header = [
            "Authorization" => "Bearer $token"
        ];
        $response = $this->json("PUT", "/api/role/" . $role->slug, $data, $header);
        $response->assertStatus(403);
    }

    public function testDeleteRoleAsAdministratorShouldBeAllowed()
    {
        $data = [
            "name" => "TestRoleDeleteAsAdministrator",
            "slug" => "testroleDeleteAsAdministrator",
            "permissions" => [
                "test.index" => true,
                "test.get" => true,
                "test.update" => true,
                "test.store" => true,
                "test.delete" => true,
            ]
        ];
        $role = $this->createRole($data);
        $token = $this->getTokenByRole("administrator");
        $header = [
            "Authorization" => "Bearer $token"
        ];
        $response = $this->json("PUT", "/api/role/" . $role->slug, $data, $header);
        $response->assertStatus(200);
    }
}
