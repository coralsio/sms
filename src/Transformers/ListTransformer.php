<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\SMS\Models\SMSList;

class ListTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('sms.models.list.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param SMSList $smsList
     * @return array
     * @throws \Throwable
     */
    public function transform(SMSList $smsList)
    {

        $transformedArray = [
            'id' => $smsList->id,
            'code' => HtmlElement('a', ['href' => $smsList->getShowURL()], $smsList->code),
            'label' => $smsList->label,
            'checkbox' => $this->generateCheckboxElement($smsList),
            'phone_numbers_count' => $smsList->phone_numbers_count,
            'status' => formatStatusAsLabels($smsList->status),
            'created_at' => format_date($smsList->created_at),
            'updated_at' => format_date($smsList->updated_at),
            'action' => $this->actions($smsList)
        ];

        return parent::transformResponse($transformedArray);
    }
}
