<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\SMS\Models\Message;

class MessageHistoryTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('sms.models.message_history.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Message $message
     * @return array
     * @throws \Throwable
     */
    public function transform(Message $message)
    {
        $createdAt = format_date_time($message->created_at);
        $status = str_replace('_', ' ', ucfirst($message->status));

        $transformedArray = [
            'id' => $message->id,
            'messageable' => sprintf(
                "<a href='%s'>%s</a>",
                $message->messageable->getShowURL() . "/messages",
                $message->messageable->getIdentifier()
            ),
            'body' => $message->body,
            'status' => $status,
            'created_at' => $createdAt,
            'updated_at' => format_date($message->updated_at),
            'action' => $this->actions($message),
        ];

        return parent::transformResponse($transformedArray);
    }
}
