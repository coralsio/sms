<div class="row">
    <div class="col-md-12">
        {!! Form::open(['url'=>url(url('sms/phone-numbers/bulk-action')),'class'=>'ajax-form','data-page_action'=>'messagesSent']) !!}
        {!! Form::hidden('action','send_messages') !!}
        {!! Form::hidden('selection',null,['id'=>'phone_numbers_selections_ids']) !!}
        <div class="row">
            <div class="col-md-6">
                {!! CoralsForm::select2('provider','SMS::attributes.provider.provider',\SMS::getActiveProviders() ,true) !!}
                {!! CoralsForm::select('','SMS::labels.phone_number.pre_defined_messages',
                    \ListOfValues::get('sms_pre_defined_messages',true,'active',true)->pluck('label','value'),false,null,
                    ['id'=>'predefined-messages'],'select2') !!}
            </div>
            <div class="col-md-6">
                @include('SMS::messages.partials.sms_parameters')
            </div>
        </div>

        {!! CoralsForm::textarea('body','SMS::labels.phone_number.body', true, null, [
            'rows' => 5,
            'class' => 'limited-text',
            'maxlength' => 999,
            'help_text' => '<span class="limit-counter">0</span>',
            'placeholder' => trans('SMS::labels.phone_number.type_message'),
            'style' => 'resize:none',
        ]) !!}

        {!! CoralsForm::formButtons(trans('SMS::labels.phone_number.send_message'),[],['show_cancel'=>false]) !!}


        {!! Form::close() !!}
    </div>
</div>


<script>

    $('#predefined-messages').on('change', function (e) {
        $('#body').val($(this).val());
    });

    checkedIds = $('#PhoneNumbersDataTable tbody input:checkbox:checked').map(function () {
        return $(this).val();
    }).get();

    $('#phone_numbers_selections_ids').val(JSON.stringify(checkedIds));

    function messagesSent(r, f) {
        closeModal(r, f);
        $('#PhoneNumbersDataTable').DataTable().ajax.reload();
    }

</script>
