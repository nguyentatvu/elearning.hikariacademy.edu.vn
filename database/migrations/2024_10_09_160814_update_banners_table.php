<?php

use App\Enums\BannerDisplayType;
use App\Enums\BannerGroup;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('banners');
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('display_type', BannerDisplayType::getValues())->default(BannerDisplayType::SINGLE);
            $table->enum('group', BannerGroup::getValues())->default(BannerGroup::HOME);
            $table->string('image');
            $table->string('to_url')->nullable()->comment('Destination link when clicking on banner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('image');
            $table->string('link')->nullable()->comment('Destination link when clicking on banner');
            $table->timestamps();
        });
    }
}
