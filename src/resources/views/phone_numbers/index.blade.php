@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_phone_numbers') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    @parent
    @if(user()->can('create',\Corals\Modules\SMS\Models\PhoneNumber::class))
        {!! CoralsForm::link(url('sms/phone-numbers/import-csv-modal'),
            trans('SMS::labels.phone_number.import'),
            ['class' => 'btn btn-primary','data'=>[
                'action' => 'modal-load',
                'title' => trans('SMS::labels.phone_number.import'),
            ]]) !!}
    @endif

    @include('SMS::messages.partials.send_quick_message_action')

@endsection
