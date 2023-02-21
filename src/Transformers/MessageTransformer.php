<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\SMS\Models\Message;

class MessageTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('sms.models.message.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Message $message
     * @return array
     * @throws \Throwable
     */
    public function transform(Message $message)
    {
        if ($message->type === 'incoming') {
            $initiator = $message->messageable->getIdentifier();
        } else {
            $initiator = trim(optional($message->provider)->name . ' | ' . $message->creator->email, ' |');
        }

        $createdAt = format_date_time($message->created_at);

        $statusText = str_replace('_', ' ', ucfirst($message->status));

        switch ($message->status) {
            case 'queued':
                $status = formatStatusAsLabels($statusText);
                break;
            case 'sent_to_provider':
                $status = formatStatusAsLabels($statusText, ['level' => 'success']);
                break;
            case 'received':
                $status = formatStatusAsLabels($statusText, ['level' => 'info']);
                break;
            default:
                $status = formatStatusAsLabels($statusText, ['level' => 'primary']);
        }

        $transformedArray = [
            'id' => $message->id,
            'messageable' => $message->messageable->getIdentifier(),
            'phone' => sprintf("<a href='%s'>%s</a>", $message->messageable->getShowURL() . "/messages",
                $message->messageable->getPhoneNumber()),
            'initiator' => $initiator,
            'status' => $status,
            'body' => $message->body,
            'provider' => optional($message->provider)->name ?? '-',
            'info' => join(' | ', [$initiator, $status, $createdAt]),
            'created_at' => $createdAt,
            'updated_at' => format_date($message->updated_at),
            'action' => $this->actions($message)
        ];

        return parent::transformResponse($transformedArray);
    }
}
