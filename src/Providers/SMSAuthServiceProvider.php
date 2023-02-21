<?php

namespace Corals\Modules\SMS\Providers;

use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Models\Provider;
use Corals\Modules\SMS\Models\Message;
use Corals\Modules\SMS\Models\SMSList;
use Corals\Modules\SMS\Policies\ListPolicy;
use Corals\Modules\SMS\Policies\PhoneNumberPolicy;
use Corals\Modules\SMS\Policies\ProviderPolicy;
use Corals\Modules\SMS\Policies\MessagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SMSAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Provider::class => ProviderPolicy::class,
        PhoneNumber::class => PhoneNumberPolicy::class,
        Message::class => MessagePolicy::class,
        SMSList::class => ListPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
