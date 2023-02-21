<?php

namespace Corals\Modules\SMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\SMS\Models\Message;
use Corals\Modules\SMS\Transformers\MessageTransformer;
use Yajra\DataTables\EloquentDataTable;

class MessagesHistoryDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('sms.models.message_history.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new MessageTransformer());
    }

    /**
     * @param Message $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Message $model)
    {
        return $model->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'phone' => ['title' => trans('SMS::attributes.phone_number.phone')],
            'messageable' => ['title' => trans('SMS::labels.message_history.messageable')],
            'body' => ['title' => trans('SMS::labels.message_history.body')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'provider' => ['title' => trans('SMS::attributes.provider.provider')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            'has_action' => false
        ];
    }
}
