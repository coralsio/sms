<?php

namespace Corals\Modules\SMS\Jobs;

use Corals\Modules\SMS\Models\Message;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Models\SMSList;
use Corals\Utility\Webhook\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class HandleSMSReceive implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var Webhook
     */
    public $webhook;

    /**
     * HandleSMSReceive constructor.
     * @param Webhook $webhook
     */
    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function handle()
    {
        logger("======================================================");
        logger("HandleSMSReceive Job");
        logger("{$this->webhook->event_name}::{$this->webhook->id} Job Started");
        logger("------------------------------------------------------");

        try {
            $webhook = $this->webhook;

            if ($webhook->status == 'processed') {
                $this->webhook->saveException(new \Exception(trans(
                    'Utility::exceptions.webhook.already_processed',
                    ['name' => $webhook->event_name, 'id' => $webhook->id]
                )));

                return;
            }

            $payload = $webhook->payload;

            $this->handleReceivedMessage($payload);

            $webhook->markAs('processed');
        } catch (\Exception $exception) {
            $this->webhook->saveException($exception);
            logger("{$this->webhook->event_name}::{$this->webhook->id} Exception");
            logger('Exception: ' . $exception->getMessage());
        } finally {
            logger("------------------------------------------------------");
            logger("{$this->webhook->event_name}::{$this->webhook->id} Job Ended");
            logger("HandleSMSReceive Job Ended");
            logger("======================================================");
        }
    }

    /**
     * @param $payload
     * @throws \Exception
     */
    public function handleReceivedMessage($payload)
    {
        if (! empty($payload['From']) && ! empty($payload['Body'])) {
            //twilio
            $from = getCleanedPhoneNumber(Arr::get($payload, 'From'));
            $body = Arr::get($payload, 'Body');
            $to = Arr::get($payload, 'To');
        } elseif (! empty($payload['msisdn']) && ! empty($payload['text'])) {
            //nexmo
            $from = getCleanedPhoneNumber(Arr::get($payload, 'msisdn'));
            $body = Arr::get($payload, 'text');
            $to = Arr::get($payload, 'to');
        } else {
            throw new \Exception('Cannot handle webhook payload');
        }

        $from = getCleanedPhoneNumber($from);

        $latestMessage = Message::query()->where('to', $from)->latest()->first();

        $properties = [];

        $status = 'received';

        if ($latestMessage) {
            $messageable = $latestMessage->messageable;
            if ($latestMessage->getProperty('is_confirmation')) {
                $properties = Arr::except($latestMessage->properties, ['is_confirmation', 'payload']);

                $properties['confirmation_message_id'] = $latestMessage->id;

                $status = 'conf_pending';
            }
        } else {
            $mainListId = SMSList::first();

            $messageable = PhoneNumber::query()->create([
                'phone' => $from,
                'name' => $from,
                'list_id' => $mainListId->id,
                'status' => 'active',
            ]);
        }

        $properties['payload'] = $payload;

        $data = [
            'to' => $to,
            'from' => $from,
            'type' => 'incoming',
            'body' => $body,
            'status' => $status,
            'properties' => $properties,
        ];

        if (false && $this->shouldBeConcat($payload)) {
            //no need to create message
            //concat with the original message
            $this->handleMessageConcatenation($payload, $to, $from, $body);
        } else {
            $message = $messageable->messages()->create($data);
        }
    }

    /**
     * @param $payload
     * @param $to
     * @param $from
     * @param $body
     */
    protected function handleMessageConcatenation($payload, $to, $from, $body): void
    {
        $originalMessage = Message::query()
            ->where('to', $to)
            ->where('from', $from)
            ->whereRaw(
                "json_unquote(json_extract(properties,'$.\"payload\".\"concat-ref\"')) = ?
                    and json_unquote(json_extract(properties,'$.\"payload\".\"concat-part\"')) = 1",
                [Arr::get($payload, 'concat-ref')]
            )->first();
        if ($originalMessage) {
            $originalMessage->update([
                'body' => $originalMessage->body . $body,
            ]);
        }
    }

    /**
     * @param $payload
     * @return bool
     */
    protected function shouldBeConcat($payload): bool
    {
        return Arr::get($payload, 'concat', false) && Arr::get($payload, 'concat-part') != 1;
    }
}
