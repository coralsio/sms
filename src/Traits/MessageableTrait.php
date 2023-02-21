<?php

namespace Corals\Modules\SMS\Traits;

use Corals\Modules\SMS\Models\Message;

trait MessageableTrait
{
    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        if (! is_null($phoneNumber = $this->phone_number)) {
            return $phoneNumber;
        }

        if (! is_null($phone = $this->phone)) {
            return $phone;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    /**
     * @return string|string[]|null
     */
    public function routeNotificationForNexmo()
    {
        return $this->getPhoneNumber();
    }

    /**
     * @return mixed
     */
    public function routeNotificationForTwilio()
    {
        return $this->getPhoneNumber();
    }

    /**
     * @return mixed
     */
    abstract public function getSMSBodyParameters(): array;

    /**
     * @return mixed
     */
    abstract public static function getSMSBodyDescriptions(): array;
}
