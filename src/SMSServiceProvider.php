<?php

namespace Corals\Modules\SMS;

use Corals\Modules\SMS\Facades\SMS;
use Corals\Modules\SMS\Hooks\MessageableTrait;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Models\Provider;
use Corals\Modules\SMS\Models\SMSList;
use Corals\Modules\SMS\Providers\SMSAuthServiceProvider;
use Corals\Modules\SMS\Providers\SMSObserverServiceProvider;
use Corals\Modules\SMS\Providers\SMSRouteServiceProvider;
use Corals\Modules\Utility\Webhook\Facades\Webhooks;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Corals\User\Models\User;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'SMS');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'SMS');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerCustomFieldsModels();

        $this->registerWebhooks();
        $this->registerModulesPackages();

        $messageableTrait = new MessageableTrait();
        User::mixin($messageableTrait);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sms.php', 'sms');

        $this->app->register(SMSRouteServiceProvider::class);
        $this->app->register(SMSAuthServiceProvider::class);
        $this->app->register(SMSObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('SMS', SMS::class);
        });
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(PhoneNumber::class);
        Settings::addCustomFieldModel(Provider::class);
        Settings::addCustomFieldModel(SMSList::class);
    }

    /**
     *
     */
    protected function registerWebhooks()
    {
        foreach (config('sms.webhook.events', []) as $eventName => $jobClass) {
            Webhooks::registerEvent($eventName, $jobClass);
        }
    }

    protected function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/sms');
    }
}
