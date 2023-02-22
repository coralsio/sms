<?php

//Provider
Breadcrumbs::register('sms_providers', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('SMS::module.provider.title'), url(config('sms.models.provider.resource_url')));
});

Breadcrumbs::register('sms_provider_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('sms_providers');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('sms_provider_show', function ($breadcrumbs) {
    $breadcrumbs->parent('sms_providers');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//phone_numbers

Breadcrumbs::register('sms_phone_numbers', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('SMS::module.phone_number.title'), url(config('sms.models.phone_number.resource_url')));
});

Breadcrumbs::register('sms_phone_numbers_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('sms_phone_numbers');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('sms_phone_numbers_show', function ($breadcrumbs) {
    $breadcrumbs->parent('sms_phone_numbers');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//messages

Breadcrumbs::register('sms_messages', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('SMS::module.message.title'), url(config('sms.models.message.resource_url')));
});



//messages-history

Breadcrumbs::register('sms_messages_history', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('SMS::module.message_history.title'), url(config('sms.models.message_history.resource_url')));
});


//lists

Breadcrumbs::register('sms_lists', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('SMS::module.list.title'), url(config('sms.models.list.resource_url')));
});

Breadcrumbs::register('sms_list_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('sms_lists');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('sms_list_show', function ($breadcrumbs) {
    $breadcrumbs->parent('sms_lists');
    $breadcrumbs->push(view()->shared('title_singular'));
});
