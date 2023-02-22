<?php

namespace Corals\Modules\SMS\Jobs;

use Corals\Modules\SMS\Models\Message;
use Corals\Modules\Utility\Webhook\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class HandleSMSDelivery implements ShouldQueue
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
        logger("HandleSMSDelivery Job");
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

            $this->handleUpdateMessageStatus($payload);

            $webhook->markAs('processed');
        } catch (\Exception $exception) {
            $this->webhook->saveException($exception);
            logger("{$this->webhook->event_name}::{$this->webhook->id} Exception");
            logger('Exception: ' . $exception->getMessage());
        } finally {
            logger("------------------------------------------------------");
            logger("{$this->webhook->event_name}::{$this->webhook->id} Job Ended");
            logger("HandleSMSDelivery Job Ended");
            logger("======================================================");
        }
    }

    /**
     * @param $payload
     * @throws \Exception
     */
    protected function handleUpdateMessageStatus($payload): void
    {
        if (! empty($payload['To']) && ! empty($payload['SmsStatus'])) {
            //twilio
            $msisdn = getCleanedPhoneNumber(Arr::get($payload, 'To'));
            $status = Arr::get($payload, 'SmsStatus');
        } elseif (! empty($payload['msisdn']) && ! empty($payload['status'])) {
            //nexmo
            $msisdn = getCleanedPhoneNumber(Arr::get($payload, 'msisdn'));
            $status = Arr::get($payload, 'status');
        } else {
            throw new \Exception('Cannot handle webhook payload');
        }

        $lastMessage = Message::query()->where('to', $msisdn)
            ->where('type', 'outgoing')
            ->latest()
            ->first();

        if (! $lastMessage) {
            throw new \Exception("No such messages found with [$msisdn] phone number");
        }

        $properties = $lastMessage->properties ?? [];

        $delivery_status_dump = $properties['delivery_status_dump'] ?? [];

        $delivery_status_dump[] = $payload;

        $properties['delivery_status_dump'] = $delivery_status_dump;

        $lastMessage->update([
            'status' => $status,
            'properties' => $properties,
        ]);
    }
}
