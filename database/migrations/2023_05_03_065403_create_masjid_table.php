<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('masjid', function (Blueprint $table) {
            $table->id();
            $table->string("nama_masjid");
            $table->string("kota");
            $table->string("provinsi");
            $table->string("alamat");
            $table->integer("tahun_berdiri")->nullable();
            $table->string("link_website")->nullable();
            $table->string("link_googlemap")->nullable();
            $table->string("deskripsi")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masjid');
    }
};
