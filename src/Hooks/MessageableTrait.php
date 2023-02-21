<?php

namespace Corals\Modules\SMS\Hooks;


use Corals\Modules\SMS\Models\Message;

class MessageableTrait
{
    function getPhoneNumber()
    {
        return function () {
            if (!is_null($phoneNumber = $this->phone_number)) {
                return $phoneNumber;
            }

            if (!is_null($phone = $this->phone)) {
                return $phone;
            }

            return null;
        };
    }

    function messages()
    {
        return function () {
            return $this->morphMany(Message::class, 'messageable');
        };
    }

    function routeNotificationForNexmo()
    {
        return function () {
            return $this->getPhoneNumber();
        };
    }

    function routeNotificationForTwilio()
    {
        return function () {
            return $this->getPhoneNumber();
        };
    }


    function getSMSBodyParameters()
    {
        return function () {
            return [];
        };
    }

    static function getSMSBodyDescriptions()
    {
        return function () {
            return [];
        };
    }
}
