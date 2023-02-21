<?php

namespace Corals\Modules\SMS\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\SMS\database\migrations\SMSTables;
use Corals\Modules\SMS\database\seeds\SMSDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $module_public_path = __DIR__ . '/../public';

    protected $migrations = [
        SMSTables::class
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $smsDatabaseSeeder = new SMSDatabaseSeeder();

        $smsDatabaseSeeder->run();
    }
}
