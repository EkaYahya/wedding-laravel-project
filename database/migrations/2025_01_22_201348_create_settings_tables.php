<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTables extends Migration
{
    public function up()
    {
        // Tabel untuk events
        Schema::create('settings_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('user_name');
            $table->date('event_date');
            $table->integer('invitation_count')->default(0);
            $table->string('invitation_link');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // Tabel untuk template WhatsApp
        Schema::create('settings_wa_templates', function (Blueprint $table) {
            $table->id();
            $table->text('template_text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings_events');
        Schema::dropIfExists('settings_wa_templates');
    }
}

