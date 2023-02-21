@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_provider_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @component('components.box')
        <div class="row">
            <div class="col-md-6">

                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('SMS::attributes.provider.name')</th>
                            <td>{!!$provider->presentStripTags('name') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('SMS::attributes.provider.provider')</th>
                            <td>{!!$provider->present('provider') !!}</td>
                        </tr>


                        <tr>
                            <th>@lang('SMS::attributes.provider.phone')</th>
                            <td>{!!$provider->present('phone') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('SMS::attributes.provider.keys')</th>
                            <td>{!!$provider->present('keys') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Corals::attributes.status')</th>
                            <td>{!!$provider->present('status') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Corals::attributes.created_at')</th>
                            <td>{!!$provider->present('created_at') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Corals::attributes.updated_at')</th>
                            <td>{!!$provider->present('updated_at') !!}</td>
                        </tr>

                    </table>


                </div>


            </div>
        </div>
    @endcomponent
@endsection

