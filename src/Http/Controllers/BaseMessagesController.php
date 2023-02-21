<?php


namespace Corals\Modules\SMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\SMS\DataTables\MessagesDataTable;
use Corals\Modules\SMS\Http\Requests\MessageRequest;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Services\MessageService;
use Illuminate\Http\Request;


class BaseMessagesController extends BaseController
{
    /**
     * @var MessageService
     */
    public $messageService;

    /**
     * @var
     */
    public $messageableClass;

    /**
     * MessagesController constructor.
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;

        $this->resource_url = config('sms.models.message.resource_url');

        $this->title = trans('SMS::module.message.title');
        $this->title_singular = trans('SMS::module.message.title_singular');

        //set the default messageable class ...
        if (is_null($this->messageableClass)) {
            $this->messageableClass = PhoneNumber::class;
        }

        parent::__construct();
    }


    /**
     * @param MessageRequest $request
     * @param MessagesDataTable $dataTable
     * @return mixed
     */
    public function index(MessageRequest $request, MessagesDataTable $dataTable)
    {
        return $dataTable->render('SMS::messages.index');
    }

    /**
     * @param MessageRequest $request
     * @param $messageableHashedId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(MessageRequest $request, $messageableHashedId)
    {
        $messageable = $this->messageableClass::findByHash($messageableHashedId);

        $this->setViewSharedData([
            'title_singular' => trans('SMS::labels.phone_number.send_message_to_title', [
                'to' => sprintf('%s [%s]', $messageable->getIdentifier(), $messageable->getPhoneNumber())
            ])
        ]);

        return $this->messageService->messageableThread($messageable);
    }

    /**
     * @param Request $request
     * @param $messageable
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendMessage(Request $request, $messageable)
    {
        $this->validate($request, ['messageable_type' => 'required', 'body' => 'required', 'provider' => 'required']);

        try {

            $messageable = $request->get('messageable_type')::findByHash($messageable);

            $message = $this->messageService->sendMessage(
                $request, $messageable
            );

            return response()->json([
                'level' => 'success',
                'message' => trans('SMS::labels.phone_number.message_sent_successfully'),
                'data' => [
                    'message' => [
                        'body' => $message->body,
                        'created_at' => $message->present('created_at'),
                        'info' => $message->present('info'),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_exception($e);

            return response()->json([
                'message' => $e->getMessage(),
                'level' => 'error'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function sendQuickMessageModal(Request $request)
    {
        abort_if(!$request->ajax(), 404);

        return view("SMS::messages.partials.send_quick_message_modal");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendQuickMessage(Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
            'provider' => 'required',
            'phone' => 'required'
        ]);

        try {

            $this->messageService->sendQuickMessage($request);

            return response()->json([
                'level' => 'success',
                'message' => trans('SMS::labels.phone_number.message_sent_successfully'),
            ]);

        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => $e->getMessage(),
                'level' => 'error'
            ]);
        }
    }

}
