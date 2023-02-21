<?php

namespace Corals\Modules\SMS\Hooks;

use Corals\Modules\SMS\Models\Message;

class MessageableTrait
{
    public function getPhoneNumber()
    {
        return function () {
            if (! is_null($phoneNumber = $this->phone_number)) {
                return $phoneNumber;
            }

            if (! is_null($phone = $this->phone)) {
                return $phone;
            }

            return null;
        };
    }

    public function messages()
    {
        return function () {
            return $this->morphMany(Message::class, 'messageable');
        };
    }

    public function routeNotificationForNexmo()
    {
        return function () {
            return $this->getPhoneNumber();
        };
    }

    public function routeNotificationForTwilio()
    {
        return function () {
            return $this->getPhoneNumber();
        };
    }

    public function getSMSBodyParameters()
    {
        return function () {
            return [];
        };
    }

    public static function getSMSBodyDescriptions()
    {
        return function () {
            return [];
        };
    }
}
