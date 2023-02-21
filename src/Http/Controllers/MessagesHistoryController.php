<?php

namespace Corals\Modules\SMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\SMS\DataTables\MessagesHistoryDataTable;
use Corals\Modules\SMS\Http\Requests\MessageRequest;

class MessagesHistoryController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('sms.models.message_history.resource_url');

        $this->title = trans('SMS::module.message_history.title');
        $this->title_singular = trans('SMS::module.message_history.title_singular');

        parent::__construct();
    }

    /**
     * @param MessageRequest $request
     * @param MessagesHistoryDataTable $dataTable
     * @return mixed
     */
    public function __invoke(MessageRequest $request, MessagesHistoryDataTable $dataTable)
    {
        return $dataTable->render("SMS::messages_history.index");
    }
}
