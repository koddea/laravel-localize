<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateLocalesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('locales', function (Blueprint $table) {

            $table->increments('id');

            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->integer('order')->default(-99);
            $table->tinyInteger('is_default')->default(0);

            $table->timestamps();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('locales');
    }
}