<?php

return [
    'models' => [
        'provider' => [
            'presenter' => \Corals\Modules\SMS\Transformers\ProviderPresenter::class,
            'resource_url' => 'sms/providers',
            'supported_providers' => [
                'nexmo' => [
                    'name' => 'nexmo',
                    'keys' => [
                        'NEXMO_KEY',
                        'NEXMO_SECRET',
                    ],
                ],
                'twilio' => [
                    'name' => 'twilio',
                    'keys' => [
                        'TWILIO_ACCOUNT_SID',
                        'TWILIO_AUTH_TOKEN',
                    ],
                ],
            ],
        ],
        'phone_number' => [
            'presenter' => \Corals\Modules\SMS\Transformers\PhoneNumberPresenter::class,
            'resource_url' => 'sms/phone-numbers',
            'actions' => [
                'sendMessage' => [
                    'href_pattern' => ['pattern' => '[arg]/messages', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('SMS::labels.phone_number.send_message');"]],
                    'data' => [
                    ],
                ],
            ],
        ],
        'message' => [
            'presenter' => \Corals\Modules\SMS\Transformers\MessagePresenter::class,
            'resource_url' => 'sms/messages',
            'actions' => [
                'edit' => [],
                'delete' => [],
            ],
        ],
        'message_history' => [
            'resource_url' => 'sms/messages-history',
        ],
        'list' => [
            'presenter' => \Corals\Modules\SMS\Transformers\ListPresenter::class,
            'resource_url' => 'sms/lists',
            'actions' => [
                'sendListMessage' => [
                    'href_pattern' => ['pattern' => '[arg]/send-list-message-modal', 'replace' => ['return $object->getShowURL();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('SMS::labels.phone_number.send_message');"]],
                    'data' => [
                        'title' => 'Send Message',
                        'action' => 'modal-load',
                    ],
                ],
            ],

        ],
    ],
    'webhook' => [
        'events' => [
            'sms_receive' => \Corals\Modules\SMS\Jobs\HandleSMSReceive::class,
            'sms_delivery' => \Corals\Modules\SMS\Jobs\HandleSMSDelivery::class,
        ],
    ],
];
