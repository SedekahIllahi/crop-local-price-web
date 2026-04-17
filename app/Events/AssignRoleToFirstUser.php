<?php

namespace App\Events;

use App\Enums\RoleEnum;
use Spatie\Permission\Contracts\Role;
use Illuminate\Queue\SerializesModels;

class AssignRoleToFirstUser
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->user->assignRole(RoleEnum::SUPERADMIN->value);
    }
}
