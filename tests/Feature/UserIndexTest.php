<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Transformers\UserTransformer;
use Tests\Traits\userTraits;
use Illuminate\Support\Facades\Auth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\JsonApiSerializer;

class UserIndexTest extends TestCase
{
    use userTraits;

    /**
     * test get all users as administraotr
     *
     * @todo Assert expected result array
     * @return void
     */
    public function testIndexAllUserAsAdminsShouldBeAllowed()
    {
        $user = $this->createUser('administrator');
        $token = $this->getTokenByRole("administrator", $user->id);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testIndexAllUserAsAdminsShouldBeAllowedWhenMfaEnabled()
    {
        $user = $this->createUser('administrator', true, true);
        $token = $this->getTokenByRole("administrator", $user->id, true);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testIndexAllUserAsAdminsShouldNotBeAllowedWhenMfaEnabledButNotMfaVerified()
    {
        $user = $this->createUser('administrator', true, true);
        $token = $this->getTokenByRole("administrator", $user->id, false);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test get all user as moderator
     *  
     * @todo Assert expected result array
     * @return void
     */
    public function testIndexAllUserAsModeratorShouldBeAllowed()
    {
        $user = $this->createUser('moderator');
        $token = $this->getTokenByRole("moderator", $user->id);
        # $expected_result = User::paginate()->toArray();
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testIndexAllUserAsModeratorShouldBeAllowedWhenMfaEnabled()
    {
        $user = $this->createUser('moderator', true, true);
        $token = $this->getTokenByRole("moderator", $user->id, true);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testIndexAllUserAsModeratorShouldNotBeAllowedWhenMfaEnabledButNotMfaVerified()
    {
        $user = $this->createUser('moderator', true, true);
        $token = $this->getTokenByRole("moderator", $user->id, false);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test get all users as subscriber
     *
     * @todo Assert expected result array
     * @return void
     */
    public function testIndexAllUserAsSubscriberShouldBeAllowed()
    {
        $user = $this->createUser('subscriber');
        $token = $this->getTokenByRole("subscriber", $user->id);
        # $expected_result = User::paginate()->toArray();
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testIndexAllUserAsSubscriberShouldBeAllowedWhenMfaEnabled()
    {
        $user = $this->createUser('subscriber', true, true);
        $token = $this->getTokenByRole("subscriber", $user->id, true);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function testIndexAllUserAsSubscriberShouldNotBeAllowedWhenMfaEnabledButNotMfaVerified()
    {
        $user = $this->createUser('subscriber', true, true);
        $token = $this->getTokenByRole("subscriber", $user->id, false);
        $response = $this->json("GET", route("user.index"), [], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(401);
    }

    public function testIndexAllUsersAsAdministratorByRoleAdministratorShouldBeAllowed()
    {
        $user = $this->createUser("administrator", true, true);
        $token = $this->getTokenByRole("administrator", $user->id, true);
        $userPaginator = User::join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', 'roles.id')
            ->where('roles.slug', 'administrator')
            ->paginate();

        $userCollection = $userPaginator->getCollection();

        $expected_response = fractal()
            ->collection($userCollection)
            ->transformWith(new UserTransformer)
            ->serializeWith(new JsonApiSerializer())
            ->paginateWith(new IlluminatePaginatorAdapter($userPaginator))
            ->toArray();


        $response = $this->json("GET", route("user.index"), [
            "role" => "administrator"
        ], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
        $response->assertJson($expected_response);
        $user->delete();
    }

    public function testIndexAllUsersAsAdministratorByRoleModeratorShouldBeAllowed()
    {
        $user = $this->createUser("administrator", true, true);
        $token = $this->getTokenByRole("administrator", $user->id, true);
        $response = $this->json("GET", route("user.index"), [
            "role" => "administrator"
        ], [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertStatus(200);
        $user->delete();
    }
}
