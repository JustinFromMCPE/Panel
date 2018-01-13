<?php

namespace Pterodactyl\Http\Requests\API\Admin\Users;

use Pterodactyl\Models\User;

class GetUserRequest extends GetUsersRequest
{
    /**
     * Determine if the requested user exists on the Panel.
     *
     * @return bool
     */
    public function resourceExists(): bool
    {
        $user = $this->route()->parameter('user');

        return $user instanceof User && $user->exists;
    }
}