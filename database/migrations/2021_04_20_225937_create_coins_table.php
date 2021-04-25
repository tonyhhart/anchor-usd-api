<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol');
            $table->string('coinname');
            $table->string('fullname');
            $table->text('description');
            $table->string('image')->nullable();
            $table->decimal('usd_price', 30)->nullable();
            $table->decimal('usd_change_pct_day', 5)->nullable();
            $table->decimal('usd_change_pct_24_hours', 5)->nullable();
            $table->decimal('usd_change_pct_hour', 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
