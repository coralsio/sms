@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_list_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-6">
            @component('components.box')
                {!! CoralsForm::openForm($smsList) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('code','SMS::attributes.list.code',true) !!}


                        {!! CoralsForm::text('label','SMS::attributes.list.label',true) !!}

                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                        {!! CoralsForm::customFields($smsList) !!}
                    </div>
                    <div class="col-md-6">
                        <div id="provider-keys-wrapper">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($smsList) !!}
            @endcomponent
        </div>
    </div>
@endsection