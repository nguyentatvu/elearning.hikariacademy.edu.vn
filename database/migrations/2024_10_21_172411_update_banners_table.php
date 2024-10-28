<?php

use App\Enums\BannerDisplayType;
use App\Enums\BannerGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBannersTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('banners');
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('size', 50)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('display_type', 255)->nullable();
            $table->text('image')->nullable();
            $table->string('to_url', 255)->nullable()->comment('Destination link when clicking on banner');
            $table->string('position', 255)->nullable();
            $table->boolean('is_active')->default(true)->comment('Indicates if the banner is active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id'); // Ensure this matches the up method
            $table->unsignedInteger('order'); // This is fine if you want to include it
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('display_type', BannerDisplayType::getValues())->default(BannerDisplayType::SINGLE);
            $table->enum('group', BannerGroup::getValues())->default(BannerGroup::HOME);
            $table->string('image'); // Consider allowing null if necessary
            $table->string('to_url')->nullable()->comment('Destination link when clicking on banner');
            $table->timestamps();
        });
    }
}
