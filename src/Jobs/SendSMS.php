<?php

namespace Corals\Modules\SMS\Jobs;

use Corals\Modules\SMS\Notifications\SendMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $body;

    /**
     * @var
     */
    protected $message;

    /**
     * @var
     */
    protected $provider;

    /**
     * @var
     */
    protected $messagable;

    /**
     * SendSMS constructor.
     * @param $messagable
     * @param $body
     * @param $message
     * @param $provider
     */
    public function __construct($messagable, $body, $message, $provider)
    {
        $this->messagable = $messagable;
        $this->body = $body;
        $this->message = $message;
        $this->provider = $provider;
        $this->delay(2);
    }

    /**
     *
     */
    public function handle()
    {
        if (method_exists($this->messagable, 'notify')) {
            try {

                tap(new SendMessage($this->body, $this->provider, $this->message), function ($messageNotification) {
                    $channelMethod = "to" . ucfirst($messageNotification->via($this->messagable));
                    if (method_exists($messageNotification, $channelMethod)) {
                        $messageNotification->{$channelMethod}($this->messagable);
                    } else {
                        logger(sprintf("Channel Method  [%s] doesn't exists in %s", $channelMethod, SendMessage::class));
                    }
                });


            } catch (\Exception $exception) {
                logger('Classes\SMS@send');
                logger($exception->getMessage());
                logger('------------------------');
            }
        }
    }

}
