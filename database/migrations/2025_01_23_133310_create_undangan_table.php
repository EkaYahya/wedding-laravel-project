<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUndanganTable extends Migration
{
    public function up()
    {
        Schema::create('undangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_title')->nullable();
            $table->string('video')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->string('nama_laki2')->nullable();
            $table->text('keterangan_laki2')->nullable();
            $table->string('nama_prmp')->nullable();
            $table->text('keterengan_prpmp')->nullable(); // Note: there's a typo in the original request
            $table->string('nama_resepsi')->nullable();
            $table->text('keterangan_resepsi')->nullable();
            $table->string('tempat_resepsi')->nullable();
            $table->time('jam_resepsi')->nullable();
            $table->date('tanggal_resepsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('undangan');
    }
}