<?php

namespace App\Transformers;

use Carbon\Carbon;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        $created_at = Carbon::parse($user->created_at)->toFormattedDateString();
        $updated_at = Carbon::parse($user->updated_at)->toFormattedDateString();
        return [
            'id' => $user->id,
            'uuid' => $user->uuid,
            'slug' => $user->slug,
            'email' => $user->email,
            'role' => $user->roles()->pluck('slug'),
            'username' => $user->username,
            'permissions' => $user->permission,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }

    /*
    public function includeRole(User $user)
    {

    }
    */
}
