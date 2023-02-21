<?php

namespace Corals\Modules\SMS\database\seeds;

use Corals\Menu\Models\Menu;
use Corals\Modules\Utility\ListOfValue\Models\ListOfValue;
use Corals\Settings\Models\Setting;
use Corals\User\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SMSDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SMSPermissionsDatabaseSeeder::class);
        $this->call(SMSMenuDatabaseSeeder::class);
        $this->call(SMSSettingsDatabaseSeeder::class);
    }

    public function rollback()
    {
        Permission::where('name', 'like', 'SMS::%')->delete();

        Menu::where('key', 'sms')
            ->orWhere('active_menu_url', 'like', 'sms%')
            ->orWhere('url', 'like', 'sms%')
            ->delete();

        Setting::where('category', 'SMS')->delete();

        Media::whereIn('collection_name', ['sms-media-collection'])->delete();

        ListOfValue::query()->where('module', 'SMS')->delete();
    }
}
