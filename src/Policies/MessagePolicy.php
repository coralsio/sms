<?php

namespace Corals\Modules\SMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\User\Models\User;

class MessagePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.sms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->can('SMS::message.view');
    }

}
