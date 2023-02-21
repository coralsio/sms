<?php

namespace Corals\Modules\SMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\SMS\DataTables\ListsDataTable;
use Corals\Modules\SMS\Http\Requests\ListRequest;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Models\SMSList;
use Corals\Modules\SMS\Services\ListService;
use Illuminate\Http\Request;


class ListsController extends BaseController
{
    protected $listService;

    public function __construct(ListService $listService)
    {
        $this->listService = $listService;

        $this->resource_url = config('sms.models.list.resource_url');

        $this->resource_model = new SMSList();

        $this->title = trans('SMS::module.list.title');
        $this->title_singular = trans('SMS::module.list.title_singular');

        parent::__construct();
    }

    /**
     * @param ListRequest $request
     * @param ListsDataTable $dataTable
     * @return mixed
     */
    public function index(ListRequest $request, ListsDataTable $dataTable)
    {
        return $dataTable->render('SMS::lists.index');
    }

    /**
     * @param ListRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ListRequest $request)
    {
        $smsList = new SMSList();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('SMS::lists.create_edit')->with(compact('smsList'));
    }

    /**
     * @param ListRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ListRequest $request)
    {
        try {
            $smsList = $this->listService->store($request, SMSList::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, SMSList::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param ListRequest $request
     * @param SMSList $smsList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ListRequest $request, SMSList $smsList)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $smsList->getIdentifier('code')]),
            'showModel' => $smsList,
        ]);

        return view('SMS::lists.show')->with(compact('smsList'));
    }

    /**
     * @param ListRequest $request
     * @param SMSList $smsList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ListRequest $request, SMSList $smsList)
    {


        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $smsList->getIdentifier('code')])]);

        return view('SMS::lists.create_edit')->with(compact('smsList'));
    }

    /**
     * @param ListRequest $request
     * @param SMSList $smsList
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ListRequest $request, SMSList $smsList)
    {
        try {
            $this->listService->update($request, $smsList);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, SMSList::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param ListRequest $request
     * @param SMSList $smsList
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ListRequest $request, SMSList $smsList)
    {
        try {
            $this->listService->destroy($request, $smsList);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, SMSList::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param SMSList $smsList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendListMessageModal(Request $request, SMSList $smsList)
    {
        abort_if(!$request->ajax(), 404);

        return view('SMS::lists.partials.send_list_message_modal')
            ->with([
                'smsBodyDescription' => PhoneNumber::getSMSBodyDescriptions(),
                'smsList' => $smsList,
                'url' => $smsList->getShowURL() . '/send-list-message'
            ]);
    }

    /**
     * @param Request $request
     * @param SMSList $smsList
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendListMessage(Request $request, SMSList $smsList)
    {
        $this->validate($request, ['body' => 'required', 'provider' => 'required']);
        try {

            $this->listService->sendMessage($request, $smsList);

            return response()->json([
                'level' => 'success',
                'message' => trans('SMS::labels.list.message_sent')
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'level' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function sendBulkMessageModal(Request $request)
    {
        return view('SMS::lists.partials.send_list_message_modal')->with([
            'bulkAction' => true,
            'url' => url('sms/lists/send-bulk-message'),
            'smsBodyDescription' => PhoneNumber::getSMSBodyDescriptions(),
        ]);
    }

    /**
     * @param Request $request
     */
    public function sendBulkMessage(Request $request)
    {

        $request->request->add([
            'selection' => json_decode($request->get('selection'), true)
        ]);

        $request->validate([
            'body' => 'required',
            'provider' => 'required',
            'selection' => 'required|array|min:1'
        ], ['selection.required' => 'No records selected.']);


        try {

            $ids = [];

            foreach ($request->get('selection') as $selection) {
                $ids[] = hashids_decode($selection);
            }

            SMSList::query()->findMany($ids)
                ->each(function (SMSList $smsList) {
                    $this->listService->sendMessage(request(), $smsList);
                });
            $message = [
                'level' => 'success',
                'message' => trans('SMS::labels.list.messages_has_been_sent')
            ];
        } catch (\Exception $exception) {
            $message = [
                'level' => 'error',
                'message' => $exception->getMessage()
            ];

            $code = 400;
        }


        return response()->json($message, $code ?? 200);
    }
}
