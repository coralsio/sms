@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_provider_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-6">
            @component('components.box')
                {!! CoralsForm::openForm($provider) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','SMS::attributes.provider.name',true) !!}

                        {!! CoralsForm::select2('provider', 'SMS::attributes.provider.provider', array_map('strtoupper',\Arr::pluck(config('sms.models.provider.supported_providers',[]),'name','name')),true) !!}

                        {!! CoralsForm::text('phone','SMS::attributes.provider.phone',true) !!}

                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                        {!! CoralsForm::customFields($provider) !!}
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
                {!! CoralsForm::closeForm($provider) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('change', `[name='provider']`, renderProviderKeys);

        $(document).ready(renderProviderKeys);

        function renderProviderKeys() {
            let providerKey = $(`[name='provider']`).val();

            if (providerKey) {
                $('#provider-keys-wrapper').load(
                    `{{url("sms/providers/render-provider-keys/$provider->hashed_id")}}?provider_key=${providerKey}`
                );
            } else {
                $('#provider-keys-wrapper').html('');
            }

        }
    </script>
@endsection
