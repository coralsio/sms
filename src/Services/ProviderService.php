<?php

namespace Corals\Modules\SMS\Services;


use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\SMS\Models\Provider;

class ProviderService extends BaseServiceClass
{
    /**
     * @param Provider $provider
     * @param $providerKey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderProviderKeys(Provider $provider, $providerKey)
    {
        $keys = config("sms.models.provider.supported_providers.$providerKey.keys", []);

        return view("SMS::providers.partials.keys")->with(compact('keys', 'provider'));
    }
}
