<?php

namespace Corals\Modules\SMS\database\seeds;

use Illuminate\Database\Seeder;

class SMSMenuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sms_menu_id = \DB::table('menus')->insertGetId([
            'parent_id' => 1,// admin
            'key' => 'sms',
            'url' => null,
            'active_menu_url' => 'sms*',
            'name' => 'SMS',
            'description' => 'SMS Menu Item',
            'icon' => 'fa fa-envelope-open-o',
            'target' => null, 'roles' => '["1","2"]',
            'order' => 0
        ]);

        // seed children menu
        \DB::table('menus')->insert([
                [
                    'parent_id' => $sms_menu_id,
                    'key' => null,
                    'url' => config('sms.models.provider.resource_url'),
                    'active_menu_url' => config('sms.models.provider.resource_url') . '*',
                    'name' => 'Providers',
                    'description' => 'Providers List Menu Item',
                    'icon' => 'fa fa-plug',
                    'target' => null, 'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $sms_menu_id,
                    'key' => null,
                    'url' => config('sms.models.list.resource_url'),
                    'active_menu_url' => config('sms.models.list.resource_url') . '*',
                    'name' => 'Lists',
                    'description' => 'SMS Lists Menu Item',
                    'icon' => 'fa fa-list-ol',
                    'target' => null, 'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $sms_menu_id,
                    'key' => null,
                    'url' => config('sms.models.phone_number.resource_url'),
                    'active_menu_url' => config('sms.models.phone_number.resource_url') . '*',
                    'name' => 'Phone numbers',
                    'description' => 'Phone numbers List Menu Item',
                    'icon' => 'fa fa-phone',
                    'target' => null, 'roles' => '["1"]',
                    'order' => 0
                ],

                [
                    'parent_id' => $sms_menu_id,
                    'key' => null,
                    'url' => config('sms.models.message.resource_url'),
                    'active_menu_url' => config('sms.models.message.resource_url'),
                    'name' => 'Messages',
                    'description' => 'Messages List Menu Item',
                    'icon' => 'fa fa-envelope-open',
                    'target' => null, 'roles' => '["1"]',
                    'order' => 0
                ],

                [
                    'parent_id' => $sms_menu_id,
                    'key' => null,
                    'url' => config('sms.models.message_history.resource_url'),
                    'active_menu_url' => config('sms.models.message_history.resource_url') . '*',
                    'name' => 'Messages History',
                    'description' => 'Messages History List Menu Item',
                    'icon' => 'fa fa-history',
                    'target' => null, 'roles' => '["1"]',
                    'order' => 0
                ]
            ]
        );
    }
}
