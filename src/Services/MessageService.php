<?php

namespace Corals\Modules\SMS\Services;


use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\SMS\Facades\SMS;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Models\Provider;
use Corals\Modules\SMS\Models\SMSList;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MessageService extends BaseServiceClass
{
    use ValidatesRequests;

    /**
     * @param $request
     * @param $messageable
     * @param null $providerId
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendMessage($request, $messageable, $providerId = null)
    {
        $this->validate($request, ['provider' => 'required', 'body' => 'required']);

        if (is_null($providerId)) {
            $providers = Arr::wrap($request->get('provider'));
            $provider = Provider::find(Arr::random($providers));
        } else {
            $provider = Provider::find($providerId);
        }

        return SMS::send([
            'messageable_type' => $messageable,
            'messageable_id' => $messageable->id,
            'to' => $messageable->getPhoneNumber(),
            'from' => $provider->phone,
            'body' => $request->get('body'),
            'provider' => $provider
        ]);
    }

    /**
     * @param $messagable
     * @return mixed
     */
    public function getLatestUsedProviderId($messagable)
    {
        return optional($messagable->messages()
            ->where('type', 'outgoing')
            ->latest()
            ->first()
        )->provider_id;
    }

    /**
     * @param $messageable
     * @param $controller
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messageableThread($messageable)
    {
        $latestUsedProviderId = $this->getLatestUsedProviderId($messageable);

        $smsBodyDescription = $messageable->getSMSBodyDescriptions();


        return view("SMS::messages.messageable_thread")
            ->with(compact('messageable', 'latestUsedProviderId', 'smsBodyDescription'));
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendQuickMessage(Request $request)
    {
        $cleanPhone = getCleanedPhoneNumber($request->get('phone'));

        $messageable = PhoneNumber::firstOrCreate([
            'phone' => $cleanPhone
        ], [
            'name' => $cleanPhone,
            'status' => 'active',
            'list_id' => SMSList::query()->first()->id
        ]);

        $this->sendMessage($request, $messageable);
    }
}
