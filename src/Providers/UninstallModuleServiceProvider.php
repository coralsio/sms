<?php

namespace Corals\Modules\SMS\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\SMS\database\migrations\SMSTables;
use Corals\Modules\SMS\database\seeds\SMSDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        SmsTables::class
    ];

    protected function providerBooted()
    {
        $this->dropSchema();

        $smsDatabaseSeeder = new SMSDatabaseSeeder();

        $smsDatabaseSeeder->rollback();
    }
}
