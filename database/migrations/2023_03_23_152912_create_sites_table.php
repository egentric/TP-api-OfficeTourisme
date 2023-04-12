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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('nameSite');
            $table->string('descriptionSite');
            $table->string('emailSite');
            $table->string('websiteSite');
            $table->string('phoneSite');
            $table->string('zipSite');
            $table->string('citySite');
            $table->string('longitudeDegSite');
            $table->string('latitudeDegSite');
            $table->string('pictureSite');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
