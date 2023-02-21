<?php

namespace Corals\Modules\SMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\User\Models\User;

class PhoneNumberPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.sms';

    protected $skippedAbilities = ['destroy'];

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('SMS::phone_number.view')) {
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
        return $user->can('SMS::phone_number.create');
    }

    /**
     * @param User $user
     * @param PhoneNumber $phoneNumber
     * @return bool
     */
    public function update(User $user, PhoneNumber $phoneNumber)
    {
        if ($user->can('SMS::phone_number.update')) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param PhoneNumber $phoneNumber
     * @return bool
     */
    public function destroy(User $user, PhoneNumber $phoneNumber)
    {
        if ($phoneNumber->messages()->count()) {
            return false;
        }

        if ($user->can('SMS::phone_number.delete')) {
            return true;
        }

        return false;
    }
}
