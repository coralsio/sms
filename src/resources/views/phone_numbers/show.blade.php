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

@section('content')
    @component('components.box')
        <div class="row">
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-striped">

                        <tr>
                            <th>@lang('SMS::attributes.phone_number.name')</th>
                            <td>{!!$phoneNumber->presentStripTags('name') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('SMS::attributes.phone_number.last_name')</th>
                            <td>{!!$phoneNumber->present('last_name') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('SMS::attributes.phone_number.email')</th>
                            <td>{!!$phoneNumber->present('email') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('SMS::attributes.phone_number.phone')</th>
                            <td>{!!$phoneNumber->present('phone') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Corals::attributes.status')</th>
                            <td>{!!$phoneNumber->present('status') !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endcomponent
@endsection

