<?php

namespace Corals\Modules\SMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\SMS\Models\Provider;
use Corals\User\Models\User;

class ProviderPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.sms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('SMS::provider.view')) {
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
        return $user->can('SMS::provider.create');
    }

    /**
     * @param User $user
     * @param Provider $provider
     * @return bool
     */
    public function update(User $user, Provider $provider)
    {
        if ($user->can('SMS::provider.update')) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Provider $provider
     * @return bool
     */
    public function destroy(User $user, Provider $provider)
    {
        if ($user->can('SMS::provider.delete')) {
            return true;
        }

        return false;
    }
}
