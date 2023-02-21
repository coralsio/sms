<?php


namespace Corals\Modules\SMS\Services;


use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\SMS\Models\SMSList;
use Illuminate\Http\Request;

class ListService extends BaseServiceClass
{
    /**
     * @param Request $request
     * @param SMSList $smsList
     */
    public function sendMessage(Request $request, SMSList $smsList)
    {
        $messageService = new MessageService;

        $providers = $request->get('provider');
        $phoneNumbersCount = $smsList->phoneNumbers()->count();

        if (!$phoneNumbersCount) {
            return;
        }

        $providersChunkValue = $this->getProvidersChunkValue(count($providers), $phoneNumbersCount);

        $smsList->phoneNumbers()
            ->where('status', 'active')
            ->chunk($providersChunkValue, function ($phoneNumbers, $page) use ($messageService, $providers) {

                $providerIndex = ($page - 1) % count($providers);
                $providerId = $providers[$providerIndex];

                foreach ($phoneNumbers as $phoneNumber) {
                    $messageService->sendMessage(request(), $phoneNumber, $providerId);
                }
            });
    }

    /**
     * @param $providersCount
     * @param $phoneNumbersCount
     * @return float|int
     */
    protected function getProvidersChunkValue($providersCount, $phoneNumbersCount)
    {

        if ($providersCount >= $phoneNumbersCount) {
            $chunk = $providersCount / $phoneNumbersCount;
        } else {
            $chunk = $phoneNumbersCount / $providersCount;
        }

        return floor($chunk);
    }
}