<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\SMS\Models\PhoneNumber;

class PhoneNumberTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('sms.models.phone_number.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param PhoneNumber $phoneNumber
     * @return array
     * @throws \Throwable
     */
    public function transform(PhoneNumber $phoneNumber)
    {
        if ($phoneNumber->messages()->count()) {
            $phoneNumberShowURL = $phoneNumber->getShowURL() . '/messages';
        } else {
            $phoneNumberShowURL = $phoneNumber->getEditUrl();
        }

        $transformedArray = [
            'id' => $phoneNumber->id,
            'phone' => HtmlElement('a', ['href' => $phoneNumberShowURL], $phoneNumber->phone),
            'name' => $phoneNumber->name,
            'checkbox' => $this->generateCheckboxElement($phoneNumber),
            'email' => $phoneNumber->email ?? '-',
            'list' => $phoneNumber->list->label,
            'status' => formatStatusAsLabels($phoneNumber->status),
            'last_name' => $phoneNumber->last_name ?? '-',
            'created_at' => format_date($phoneNumber->created_at),
            'updated_at' => format_date($phoneNumber->updated_at),
            'action' => $this->actions($phoneNumber)
        ];

        return parent::transformResponse($transformedArray);
    }
}
