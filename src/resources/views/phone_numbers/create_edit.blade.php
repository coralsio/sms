@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_phone_numbers_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-3">
            @component('components.box')
                {!! CoralsForm::openForm($phoneNumber) !!}

                {!! CoralsForm::text('phone','SMS::attributes.phone_number.phone',true) !!}

                {!! CoralsForm::text('name','SMS::attributes.phone_number.name') !!}

                {!! CoralsForm::text('last_name','SMS::attributes.phone_number.last_name') !!}

                {!! CoralsForm::text('email','SMS::attributes.phone_number.email') !!}

                {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                {!! CoralsForm::select2('list_id',  'SMS::attributes.phone_number.list',\SMS::getSMSLists(),true) !!}

                {!! CoralsForm::customFields($phoneNumber) !!}
                {!! CoralsForm::formButtons() !!}
                {!! CoralsForm::closeForm($phoneNumber) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')

@endsection
