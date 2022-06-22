<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->unsigned()->nullable()->index();
            $table->string("manufacturer");
            $table->string("model");
            $table->string("procesor");
            $table->string("memmory");
            $table->string("drive");
            $table->string("grafic");
            $table->decimal("price", 6, 2);
            $table->string("image");
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
        Schema::dropIfExists('laptops');
    }
};
