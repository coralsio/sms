<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sms'], function () {
    Route::get('providers/render-provider-keys/{provider?}', 'ProvidersController@renderProviderKeys');

    Route::resource('providers', 'ProvidersController')->except('show');

    Route::group(['prefix' => 'lists'], function () {
        Route::get('send-bulk-message-modal', 'ListsController@sendBulkMessageModal');
        Route::post('send-bulk-message', 'ListsController@sendBulkMessage');

        Route::get('{sms_list}/send-list-message-modal', 'ListsController@sendListMessageModal');
        Route::post('{sms_list}/send-list-message', 'ListsController@sendListMessage');
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::get('send-quick-message-modal', 'BaseMessagesController@sendQuickMessageModal');
        Route::post('send-quick-message', 'BaseMessagesController@sendQuickMessage');
    });


    Route::resource('lists', 'ListsController')->parameters([
        'lists' => 'sms_list',
    ]);

    Route::resource('messages', 'BaseMessagesController')
        ->only(['index', 'show']);

    Route::get('messages-history', 'MessagesHistoryController');

    Route::post('messages/{messageableHahedId}/send-message', 'BaseMessagesController@sendMessage');

    Route::group(['prefix' => 'phone-numbers'], function () {
        Route::get('import-csv-modal', 'PhoneNumbersController@importCSVModal');
        Route::post('do-import-csv', 'PhoneNumbersController@importCSV');
        Route::get('download-import-sample', 'PhoneNumbersController@downloadImportSample');
        Route::get('{phone_number}/messages', 'PhoneNumbersController@messages');

        Route::get('send-bulk-messages-modal', 'PhoneNumbersController@sendBulkMessageModal');
        Route::post('bulk-action', 'PhoneNumbersController@bulkAction');
    });

    Route::resource('phone-numbers', 'PhoneNumbersController')->except('show');
});
