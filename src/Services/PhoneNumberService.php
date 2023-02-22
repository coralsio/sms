<?php

namespace Corals\Modules\SMS\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\SMS\Jobs\ImportPhoneNumbers;
use Corals\Modules\SMS\Models\SMSList;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\Csv\Writer;

class PhoneNumberService extends BaseServiceClass
{
    use ValidatesRequests;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function importCSV(Request $request)
    {
        $this->validate($request, [
            'csv_file' => 'required|mimes:csv,txt|max:' . maxUploadFileSize(),
            'list_id' => 'required',
        ]);

        try {
            // store file in temp folder
            $file = $request->file('csv_file');

            $importsPath = storage_path('app/sms/phone_numbers');

            $fileName = sprintf("%s_%s", Str::random(), $file->getClientOriginalName());

            $fileFullPath = $importsPath . '/' . $fileName;

            $file->move($importsPath, $fileName);

            $listId = $this->createOrGetListId($request->get('list_id'));


            ImportPhoneNumbers::dispatch($fileFullPath, [
                'list_id' => $listId,
                'status' => 'active',
            ]);

            return response()->json([
                'level' => 'success',
                'action' => 'closeModal',
                'message' => trans('SMS::labels.phone_number.file_uploaded'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'level' => 'error',
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    /**
     * @param $listId
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int|mixed|string
     */
    protected function createOrGetListId($listId)
    {
        if (is_numeric($listId)) {
            return $listId;
        }

        $listCode = Str::slug($listId);

        while (SMSList::query()->where('code', $listCode)->exists()) {
            $listCode = sprintf("%s-%s", $listCode, SMSList::query()->max('id'));
        }

        $list = SMSList::query()->create([
            'name' => $listId,
            'code' => $listCode,
            'label' => $listId,
            'status' => 'active',
        ]);

        return $list->id;
    }

    /**
     * @throws \League\Csv\CannotInsertRecord
     */
    public function downloadImportSample()
    {
        //we create the CSV into memory
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        //we insert the CSV header
        $csv->insertOne(array_keys(trans('SMS::labels.phone_number.phone-numbers-headers')));


        $csv->output(sprintf('sms_%s_%s.csv', "phone_numbers", now()->format('Y-m-d-H-i')));

        die;
    }
}
