@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('sms_list_show') }}
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
                            <th>@lang('SMS::attributes.list.code')</th>
                            <td>{!!$smsList->presentStripTags('code') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('SMS::attributes.list.label')</th>
                            <td>{!!$smsList->present('label') !!}</td>
                        </tr>



                        <tr>
                            <th>@lang('Corals::attributes.status')</th>
                            <td>{!!$smsList->present('status') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Corals::attributes.created_at')</th>
                            <td>{!!$smsList->present('created_at') !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Corals::attributes.updated_at')</th>
                            <td>{!!$smsList->present('updated_at') !!}</td>
                        </tr>

                    </table>


                </div>


            </div>
        </div>
    @endcomponent
@endsection

