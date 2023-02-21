<?php

namespace Corals\Modules\SMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\SMS\Facades\SMS;
use Corals\Modules\SMS\Models\PhoneNumber;
use Corals\Modules\SMS\Transformers\PhoneNumberTransformer;
use Yajra\DataTables\EloquentDataTable;

class PhoneNumbersDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('sms.models.phone_number.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new PhoneNumberTransformer());
    }

    /**
     * @param PhoneNumber $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PhoneNumber $model)
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
            'name' => ['title' => trans('SMS::attributes.phone_number.name')],
            'last_name' => ['title' => trans('SMS::attributes.phone_number.last_name')],
            'list' => [
                'title' => trans('SMS::attributes.phone_number.list'),
                'orderable' => false,
                'searchable' => false
            ],
            'email' => ['title' => trans('SMS::attributes.phone_number.email')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'phone' => [
                'title' => trans('SMS::attributes.phone_number.phone'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'list_id' => [
                'title' => trans('SMS::module.list.title_singular'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => SMS::getSMSLists(),
                'active' => true
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getBulkActions()
    {
        return [
            'sendMessages' => [
                'title' => trans('SMS::labels.phone_number.send_message'),
                'permission' => 'SMS::phone_number.create',
                'confirmation' => '',
                'action' => 'modal-load',
                'href' => url('sms/phone-numbers/send-bulk-messages-modal'),
                'modal-title' => trans('SMS::labels.phone_number.send_message_title')
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            'resource_url' => url(config('sms.models.phone_number.resource_url'))
        ];
    }
}
