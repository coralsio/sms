<?php

namespace Corals\Modules\SMS\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SMSTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('provider');

            $table->string('phone');

            $table->text('keys');

            $table->string('status');

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sms_lists', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code')->unique();
            $table->string('label');

            $table->string('status')->default('active');

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('sms_phone_numbers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('phone');
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('status');

            $table->unsignedInteger('list_id');

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->foreign('list_id')
                ->references('id')
                ->on('sms_lists')
                ->onDelete('Cascade')
                ->onUpdate('Cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sms_messages', function (Blueprint $table) {
            $table->increments('id');

            $table->morphs('messageable');
            $table->nullableMorphs('user');

            $table->text('body');
            $table->string('from');
            $table->string('to');
            $table->string('type');
            $table->string('status');
            $table->string('visibility');

            $table->unsignedInteger('provider_id')->nullable();

            $table->timestamp('read_at')->nullable();

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('provider_id')->references('id')
                ->on('sms_providers')
                ->onDelete('SET NULL')
                ->onUpdate('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_phone_numbers');
        Schema::dropIfExists('sms_messages');
        Schema::dropIfExists('sms_providers');
        Schema::dropIfExists('sms_lists');
    }
}
