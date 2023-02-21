<?php

namespace Corals\Modules\SMS\Classes;

use Corals\Foundation\Formatter\Formatter;
use Corals\Modules\SMS\Jobs\SendSMS;
use Corals\Modules\SMS\Models\Message;
use Corals\Modules\SMS\Models\Provider;
use Corals\Modules\SMS\Models\SMSList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SMS
{
    /**
     * @return Collection
     */
    public function getActiveProviders()
    {
        return Provider::query()
            ->where('status', 'active')
            ->pluck('name', 'id');
    }

    /**
     * @param null $status
     * @return Collection
     */
    public function getSMSLists($status = null)
    {
        $query = SMSList::query();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->pluck('label', 'id');
    }

    /**
     * @param $parameters
     * @return Builder|Model
     * @throws \Exception
     */
    public function send($parameters)
    {
        $provider = data_get($parameters, 'provider');

        $messagableType = data_get($parameters, 'messageable_type');
        $messagableId = data_get($parameters, 'messageable_id');
        $messagable = $messagableType::find($messagableId);

        $body = Formatter::format(data_get($parameters, 'body'), $messagable->getSmsBodyParameters());

        $message = Message::query()->create([
            'messageable_type' => getMorphAlias($messagable),
            'messageable_id' => $messagable->id,
            'to' => getCleanedPhoneNumber(data_get($parameters, 'to')),
            'from' => getCleanedPhoneNumber(data_get($parameters, 'from')),
            'body' => $body,
            'user_type' => getMorphAlias($user = user()),
            'user_id' => $user->id,
            'status' => 'queued',
            'type' => 'outgoing',
            'provider_id' => $provider->id,
        ]);

        SendSMS::dispatch($messagable, $body, $message, $provider);

        return $message;
    }
}
