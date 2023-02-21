@if(sizeof($smsBodyDescription) > 0)
    {{ Form::label('notification_parameters', trans('SMS::labels.message.sms_parameters')) }}
    <small class="help-block text-muted">@lang('Notification::labels.notification_parameters_help')</small>
    <ul>
        @foreach($smsBodyDescription as $parameterName => $description )
            <li>
                <a href="#" onclick="event.preventDefault();" class="copy-button"
                   data-clipboard-target="#shortcode_{{ $parameterName }}"><i
                            class="fa fa-clipboard"></i></a>
                <b id="shortcode_{{ $parameterName }}">{{ '{'.$parameterName.'}' }}</b>
                @lang($description)
            </li>
        @endforeach
    </ul>
@endif