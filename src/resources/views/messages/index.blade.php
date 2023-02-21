@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_messages') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    @parent
    @include('SMS::messages.partials.send_quick_message_action')
@endsection