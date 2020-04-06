<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->foreignId('member_request_id');
            $table->foreignId('invite_id')->nullable();
            $table->foreignId('account_id')->nullable();
            $table->timestamps();

            $table->foreign('member_request_id')->references('id')->on('member_requests')->onDelete('cascade');
            $table->foreign('invite_id')->references('id')->on('invites')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
