<style>
    .import-description-table .table {
        font-size: small;
    }

    .import-description-table .table > tbody > tr > td {
        padding: 4px;
    }

    .required-asterisk {
        color: red;
        font-size: 100%;
        top: -.4em;
    }
</style>

<div>
    {!! CoralsForm::openForm(null, ['url' => url('sms/phone-numbers/do-import-csv'), 'files' => true]) !!}
    {!! CoralsForm::file('csv_file', 'SMS::attributes.phone_number.file',true) !!}

    {!! CoralsForm::select2('list_id',  'SMS::attributes.phone_number.list',\SMS::getSMSLists('active'), true,null,['class'=>'tags']) !!}

    {!! CoralsForm::formButtons('SMS::labels.phone_number.import', [], ['show_cancel' => false]) !!}

    {!! CoralsForm::closeForm() !!}

    {!! CoralsForm::link(url('sms/phone-numbers/download-import-sample'),
   trans('SMS::labels.phone_number.download_sample'),
   ['class' => '']) !!}
</div>

<hr/>
<h4>@lang('SMS::labels.phone_number.column_description')</h4>

<div class="table-responsive import-description-table">
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 120px;">@lang('SMS::labels.phone_number.column')</th>
            <th>@lang('SMS::labels.phone_number.description')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($headers as $column => $description)
            <tr>
                <td>{{ $column }}</td>
                <td>{!! $description !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>




