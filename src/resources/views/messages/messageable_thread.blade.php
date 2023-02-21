@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_phone_numbers_show') }}
        @endslot
    @endcomponent
@endsection

@section('css')
    <style>
        .alert {
            width: 100%;
            padding: 5px;
            margin-bottom: 5px;
        }

        .messages_history .text-sm {
            font-size: 10px;
        }

        .messages_history {
            height: 500px;
            max-height: 500px;
            overflow-y: scroll;
            padding-right: 5px;
        }

        .m-separator {
            margin-bottom: 3px;
            margin-top: 5px;
            width: 50%;
            margin-left: unset;
            margin-right: unset;
        }

        .message .alert {
            display: inline-block;
        }

        .message .info-text {
            margin-bottom: 2px;
        }

        .message .info-text .label {
            font-size: 9px;
            font-weight: normal;
            padding: 2px 3px;
        }

        .alert p {
            margin: 0;
        }

        .outgoing {
            float: right;
        }

        .incoming {
            float: left;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10">
            @component('components.box',['box_class'=>'box-success'])
                {!! Form::open(['url'=>url("sms/messages/$messageable->hashed_id/send-message"),'class'=>'ajax-form','data-page_action'=>'appendMessageToHistory','id'=>'sms-form']) !!}
                {!! Form::hidden('messageable_type', get_class($messageable)) !!}
                <div class="row">
                    <div class="col-md-5">
                        <div class="messages_history">
                            @forelse($messageable->messages as $message)
                                @if($message->type == 'incoming')
                                    <div class="message incoming">
                                        <div class="info-text text-sm text-muted">
                                            {!! $message->present('info') !!}
                                        </div>
                                        <div class="alert alert-info">
                                            <p>{{ $message->body }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="message outgoing">
                                        <div class="info-text text-sm text-muted">
                                            {!! $message->present('info') !!}
                                        </div>
                                        <div class="alert alert-warning">
                                            <p>{{ $message->body }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="clearfix"></div>
                            @empty
                                <div id="no-message-yet">
                                    <p style="text-align: center">
                                        <strong>@lang("SMS::labels.phone_number.no_messages_yet")</strong>
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-5">
                                {!! CoralsForm::select2('provider','SMS::attributes.provider.provider',\SMS::getActiveProviders() ,true,$latestUsedProviderId) !!}
                                {!! CoralsForm::select('','SMS::labels.phone_number.pre_defined_messages',\ListOfValues::get('sms_pre_defined_messages',true,'active',true)->pluck('label','value'),false,null,['id'=>'predefined-messages'],'select2') !!}
                            </div>
                            <div class="col-md-7">
                                @include('SMS::messages.partials.sms_parameters')
                            </div>
                        </div>

                        {!! CoralsForm::textarea('body','SMS::labels.phone_number.body',true,null, [
                                        'rows'=>5,
                                       'class'=>'limited-text',
                                       'maxlength'=>999,
                                       'help_text'=>'<span class="limit-counter">0</span>',
                                       'placeholder'=>trans('SMS::labels.phone_number.type_message'),
                                       'style'=>'resize:none'
                                    ]) !!}
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#predefined-messages').on('change', function (e) {
            $('#body').val($(this).val());
        });

        function appendMessageToHistory(response) {
            let message;

            if (response.data) {
                message = response.data.message;
            }

            if (message) {
                let row = `<div class="message outgoing">
                                <div class="info-text text-sm text-muted">
                                    ${message.info}
                                </div>
                                <div class="alert alert-warning">
                                    <p>${message.body}</p>
                                </div>
                           </div>`;


                $('#no-message-yet').remove();

                $('.messages_history').append(row);
            }

            let providerValue = $(`[name='provider']`).val();

            clearForm(response, $('#sms-form'));


            $(`[name='provider']`)
                .val(providerValue)
                .trigger('change');

            scrollMessageHistoryToBottom();
        }

        scrollMessageHistoryToBottom();

        function scrollMessageHistoryToBottom() {
            $(".messages_history").scrollTop($(".messages_history")[0].scrollHeight);
        }

    </script>


@endsection
