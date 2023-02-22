<?php

namespace Corals\Modules\SMS\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class SendMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var
     */
    protected $provider;

    /**
     * @var
     */
    protected $message;

    /**
     * SendMessage constructor.
     * @param $body
     * @param $provider
     * @param $message
     */
    public function __construct($body, $provider, $message)
    {
        $this->body = $body;
        $this->provider = $provider;
        $this->message = $message;
        $this->delay(5);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->provider->provider;
    }

    /**
     * @param $notifiable
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function toTwilio($notifiable)
    {
        $twilio = new \Twilio\Rest\Client(
            $this->getProviderKey('TWILIO_ACCOUNT_SID'),
            $this->getProviderKey('TWILIO_AUTH_TOKEN')
        );

        $this->message->markAs('sent_to_provider');

        return $twilio->messages->create($this->message->to, [
            'body' => $this->message->body,
            'from' => getCleanedPhoneNumber($this->provider->phone),
        ]);
    }

    /**
     * @param $notifiable
     * @return \Vonage\Message\Message
     * @throws Client\Exception\Exception
     * @throws Client\Exception\Request
     * @throws Client\Exception\Server
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function toNexmo($notifiable)
    {
        $apiKey = $this->getProviderKey('NEXMO_KEY');
        $apiSecret = $this->getProviderKey('NEXMO_SECRET');

        $client = new Client(new Basic($apiKey, $apiSecret));

        $this->message->markAs('sent_to_provider');

        return $client->message()->send([
            'from' => getCleanedPhoneNumber($this->provider->phone),
            'to' => $this->message->to,
            'text' => trim($this->message->body),
        ]);
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getProviderKey($key)
    {
        return $this->provider->getProperty($key, null, null, 'keys');
    }
}
