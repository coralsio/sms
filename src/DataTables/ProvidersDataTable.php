<?php

namespace Corals\Modules\SMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\SMS\Models\Provider;
use Corals\Modules\SMS\Transformers\ProviderTransformer;
use Yajra\DataTables\EloquentDataTable;

class ProvidersDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('sms.models.provider.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new ProviderTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Provider $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Provider $model)
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
            'name' => ['title' => trans('SMS::attributes.provider.name')],
            'phone' => ['title' => trans('SMS::attributes.provider.phone')],
            'provider' => ['title' => trans('SMS::attributes.provider.provider')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}
