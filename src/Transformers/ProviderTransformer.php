<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\SMS\Models\Provider;

class ProviderTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('sms.models.provider.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Provider $provider
     * @return array
     * @throws \Throwable
     */
    public function transform(Provider $provider)
    {
        $keys = formatArrayAsLabels($provider->keys ?? [], 'default', '', true);

        $transformedArray = [
            'id' => $provider->id,
            'name' => HtmlElement('a', ['href' => $provider->getEditUrl()], $provider->name),
            'status' => formatStatusAsLabels($provider->status),
            'provider' => $provider->provider,
            'phone' => $provider->phone,
            'keys' => request()->ajax() ? generatePopover($keys) : $keys,
            'created_at' => format_date($provider->created_at),
            'updated_at' => format_date($provider->updated_at),
            'action' => $this->actions($provider)
        ];

        return parent::transformResponse($transformedArray);
    }
}
