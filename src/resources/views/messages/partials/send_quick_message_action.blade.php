@can('create',\Corals\Modules\SMS\Models\Message::class)
    {!! CoralsForm::link(url('sms/messages/send-quick-message-modal'),
        trans('SMS::labels.message.send_message'),
        ['class' => 'btn btn-primary','data'=>[
            'action' => 'modal-load',
            'title' => trans('SMS::labels.message.send_message'),
        ]]) !!}
@endcan