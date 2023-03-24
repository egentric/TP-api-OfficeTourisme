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
        //Attention la table doit etre écrit dans ce sens event_site et sans les s

        Schema::create('event_site', function (Blueprint $table) {
            $table->id();

            // Attention pas de s à site_id
            $table->bigInteger('site_id')->unsigned()->nullable();
            $table->foreign('site_id')
                ->references('id')
                ->on('sites');

            // Attention pas de s à event_id        
            $table->bigInteger('event_id')->unsigned()->nullable();
            $table->foreign('event_id')
                ->references('id')
                ->on('events');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_site');
    }
};
