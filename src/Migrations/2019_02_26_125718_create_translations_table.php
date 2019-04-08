<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('locale_id')->unsigned();

            $table->text('key');
            $table->text('value');

            $table->foreign('locale_id')->references('id')->on('locales')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->string('type')->default("frontend");

            $table->timestamps();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
}