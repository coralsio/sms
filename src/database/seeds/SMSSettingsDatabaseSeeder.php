<?php

namespace Corals\Modules\SMS\database\seeds;

use Corals\Modules\SMS\Models\SMSList;
use Corals\Utility\ListOfValue\Facades\ListOfValues;
use Corals\Utility\ListOfValue\Models\ListOfValue;
use Illuminate\Database\Seeder;

class SMSSettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedSMSLists();
        $this->seedPredefinedSMSMessages();
    }

    /**
     *
     */
    protected function seedPredefinedSMSMessages()
    {
        $smsPredefinedMessage = ListOfValue::query()->create([
            'code' => 'sms_pre_defined_messages',
            'label' => 'SMS Pre defined message',
            'module' => 'SMS',
            'status' => 'active',
        ]);

        ListOfValues::insertListOfValuesChildren($smsPredefinedMessage, [
            'pre_defined_message_example' => [
                'label' => 'pre defined message example..',
                'value' => 'pre defined message example..',
            ],
        ]);
    }

    /**
     *
     */
    protected function seedSMSLists()
    {
        SMSList::query()->create([
            'code' => 'main_list',
            'label' => 'Main List',
            'status' => 'active',
        ]);
    }
}
