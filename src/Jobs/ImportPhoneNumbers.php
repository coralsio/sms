<?php

namespace Corals\Modules\SMS\Jobs;

use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Services\PhoneNumberService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Csv\{Reader};

class ImportPhoneNumbers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importFilePath;

    protected $extraData;

    protected $phoneNumberService;

    /**
     * ImportPhoneNumbers constructor.
     * @param $importFilePath
     * @param array $extraData
     */
    public function __construct($importFilePath, $extraData = [])
    {
        $this->importFilePath = $importFilePath;
        $this->extraData = $extraData;

        $this->phoneNumberService = app(PhoneNumberService::class);
    }

    /**
     *
     */
    public function handle()
    {
        $reader = Reader::createFromPath($this->importFilePath, 'r')
            ->setHeaderOffset(0);

        foreach ($reader->getRecords() as $record) {
            DB::beginTransaction();
            try {
                $this->handleImportRecord(array_merge($record, $this->extraData));
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                report($exception);
            }
        }
    }

    /**
     * @param $record
     * @throws \Exception
     */
    protected function handleImportRecord($record)
    {

        $record = array_map('trim', $record);

        $phone = getCleanedPhoneNumber(data_get($record, 'phone'));

        $record['phone'] = $phone;

        $phoneNumber = PhoneNumber::query()
            ->where('phone', $phone)
            ->first();

        $this->validateRecord($record, $phoneNumber);


        if ($phoneNumber) {
            $phoneNumber->update($record);
        } else {
            PhoneNumber::query()->create($record);
        }
    }

    /**
     * @param array $data
     * @param $model
     * @throws \Exception
     */
    protected function validateRecord(array $data, $model)
    {
        $validator = Validator::make($data, $this->getValidationRules($model));

        if ($validator->fails()) {
            throw new \Exception(json_encode($validator->errors()->jsonSerialize()));
        }
    }

    /**
     * @param $model
     * @return array
     */
    protected function getValidationRules($model): array
    {
        return [
            'phone' => 'required',
            'email' => 'nullable|email',
        ];
    }
}
