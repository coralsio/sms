<?php

namespace Corals\Modules\SMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\SMS\Models\SMSList;
use Corals\User\Models\User;

class ListPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.sms';

    protected $skippedAbilities = ['destroy'];

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('SMS::list.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('SMS::list.create');
    }

    /**
     * @param User $user
     * @param SMSList $smsList
     * @return bool
     */
    public function update(User $user, SMSList $smsList)
    {
        if ($user->can('SMS::list.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param SMSList $smsList
     * @return bool
     */
    public function destroy(User $user, SMSList $smsList)
    {
        if ($smsList->id == 1) {
            return false;
        }

        if ($user->can('SMS::list.delete')) {
            return true;
        }

        return false;
    }

}
