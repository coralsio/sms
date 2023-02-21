<?php

namespace Corals\Modules\SMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\SMS\DataTables\PhoneNumbersDataTable;
use Corals\Modules\SMS\Http\Requests\PhoneNumberRequest;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Services\MessageService;
use Corals\Modules\SMS\Services\PhoneNumberService;
use Illuminate\Http\Request;

class PhoneNumbersController extends BaseController
{
    protected $phoneNumberService;

    public function __construct(PhoneNumberService $phoneNumberService)
    {
        $this->phoneNumberService = $phoneNumberService;

        $this->resource_url = config('sms.models.phone_number.resource_url');

        $this->resource_model = new PhoneNumber();

        $this->title = trans('SMS::module.phone_number.title');
        $this->title_singular = trans('SMS::module.phone_number.title_singular');

        parent::__construct();
    }

    /**
     * @param PhoneNumberRequest $request
     * @param PhoneNumbersDataTable $dataTable
     * @return mixed
     */
    public function index(PhoneNumberRequest $request, PhoneNumbersDataTable $dataTable)
    {
        return $dataTable->render('SMS::phone_numbers.index');
    }

    /**
     * @param PhoneNumberRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(PhoneNumberRequest $request)
    {
        $phoneNumber = new PhoneNumber();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('SMS::phone_numbers.create_edit')->with(compact('phoneNumber'));
    }

    /**
     * @param PhoneNumberRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PhoneNumberRequest $request)
    {
        try {

            $phoneNumber = $this->phoneNumberService->store($request, PhoneNumber::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, PhoneNumber::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PhoneNumberRequest $request
     * @param PhoneNumber $phoneNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(PhoneNumberRequest $request, PhoneNumber $phoneNumber)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $phoneNumber->getIdentifier()]),
            'showModel' => $phoneNumber,
        ]);

        return view('SMS::phone_numbers.show')->with(compact('phoneNumber'));
    }

    /**
     * @param PhoneNumberRequest $request
     * @param PhoneNumber $phoneNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(PhoneNumberRequest $request, PhoneNumber $phoneNumber)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $phoneNumber->getIdentifier()])]);

        return view('SMS::phone_numbers.create_edit')->with(compact('phoneNumber'));
    }

    /**
     * @param PhoneNumberRequest $request
     * @param PhoneNumber $phoneNumber
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PhoneNumberRequest $request, PhoneNumber $phoneNumber)
    {
        try {
            $this->phoneNumberService->update($request, $phoneNumber);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, PhoneNumber::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PhoneNumberRequest $request
     * @param PhoneNumber $phoneNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PhoneNumberRequest $request, PhoneNumber $phoneNumber)
    {
        try {
            $this->phoneNumberService->destroy($request, $phoneNumber);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, PhoneNumber::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function importCSVModal(Request $request)
    {
        abort_if(!$request->ajax(), 404);

        $headers = trans('SMS::labels.phone_number.phone-numbers-headers');

        return view("SMS::phone_numbers.partials.import_csv_modal")->with(compact('headers'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function importCSV(Request $request)
    {
        return $this->phoneNumberService->importCSV($request);
    }

    /**
     * @throws \League\Csv\CannotInsertRecord
     */
    public function downloadImportSample()
    {
        return $this->phoneNumberService->downloadImportSample();
    }


    /**
     * @param Request $request
     * @param PhoneNumber $phoneNumber
     * @param MessageService $messageService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messages(Request $request, PhoneNumber $phoneNumber, MessageService $messageService)
    {
        $this->setViewSharedData([
            'title_singular' => trans('SMS::labels.phone_number.send_message_to_title', [
                'to' => sprintf('%s [%s]', $phoneNumber->getIdentifier(), $phoneNumber->getPhoneNumber())
            ])
        ]);

        return $messageService->messageableThread($phoneNumber);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendBulkMessageModal(Request $request)
    {
        abort_if(!$request->ajax(), 404);

        return view('SMS::phone_numbers.partials.bulk_message_modal')
            ->with(['smsBodyDescription' => PhoneNumber::getSMSBodyDescriptions()]);
    }

    /**
     * @param BulkRequest $request
     * @param MessageService $messageService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkAction(BulkRequest $request, MessageService $messageService)
    {
        $action = $request->input('action');

        if ($action == 'send_messages') {
            $this->validate($request, ['provider' => 'required', 'body' => 'required']);
        }

        try {
            $selection = json_decode($request->input('selection'), true);

            $ids = array_map(function ($id) {
                return hashids_decode($id);
            }, $selection);


            if ($ids) {
                switch ($action) {
                    case 'send_messages':
                        foreach ($ids as $id) {
                            $messageService->sendMessage(
                                $request, PhoneNumber::query()->find($id)
                            );
                        }

                        $message = ['level' => 'success', 'message' => trans('SMS::labels.phone_number.message_sent_successfully')];

                        break;
                }
            }

        } catch (\Exception $exception) {
            log_exception($exception, PhoneNumbersController::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message ?? ['level' => 'info', 'message' => trans('SMS::labels.phone_number.no_changes_done')]);
    }
}
