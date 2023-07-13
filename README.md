# Corals SMS

- SMS module can be installed using the standard Larship Module instruction settings

- Currently, the SMS module supports 2 providers Nexmo and Twilio, once you select the provider from sender management you will promote to enter configuration settings for your provider which can be obtained using the following URLs below:

<strong>Twilio</strong>: https://www.twilio.com/docs/iam/keys/api-key-resource

<strong>Nexmo</strong>: https://developer.nexmo.com/concepts/guides/authentication

<p>&nbsp;</p>

<strong>Webhooks</strong>:  webhooks are useful to get the status of the SMS once sent to the gateway, like confirming it got received by the end-user. also when the user replies to the SMS you can receive a copy of that SMS and log in to the system, there are two types of callbacks here:

1. SMS status: the URL should be https://your-domain.com/utilities/webhooks/sms_delivery

2. Receive SMS messages: the URL should be https://your-domain.com/utilities/webhooks/sms_receive

<p>&nbsp;</p>

to add these webhooks to your SMS provider, you need to add the URLs below to the provider dashboard, below is the documentation on how to set it up:


<strong>Twilio</strong>: https://www.twilio.com/docs/usage/webhooks

<strong>Nexmo</strong>: https://developer.nexmo.com/concepts/guides/webhooks

<p>&nbsp;</p>

## Installation

You can install the package via composer:

```bash
composer require corals/sms
```

## Testing

```bash
vendor/bin/phpunit vendor/corals/sms/tests 
```
