<?php

namespace Corals\Modules\SMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\SMS\Models\SMSList;
use Corals\Modules\SMS\Transformers\ListTransformer;
use Yajra\DataTables\EloquentDataTable;

class ListsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('sms.models.list.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new ListTransformer());
    }

    /**
     * @param SMSList $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SMSList $model)
    {
        return $model->newQuery()->withCount('phoneNumbers');
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

            'code' => ['title' => trans('SMS::attributes.list.code')],
            'label' => ['title' => trans('SMS::attributes.list.label')],
            'phone_numbers_count' => ['title' => trans('SMS::labels.list.phone_numbers_count'), 'searchable' => false, 'orderable' => false],
            'status' => ['title' => trans('Corals::attributes.status')],

            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'send_message' => [
                'title' => trans('SMS::labels.list.send_bulk_message'),
                'permission' => 'SMS::list.update',
                'action' => 'modal-load',
                'href' => url('sms/lists/send-bulk-message-modal'),
                'modal-title' => strip_tags(trans('SMS::labels.list.send_bulk_message')),
                'confirmation' => ''
            ],
        ];
    }

    protected function getOptions()
    {
        return [
            'resource_url' => url('sms/lists')
        ];
    }
}
