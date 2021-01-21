<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("users_id");
            $table->integer("kelas_id");
            $table->text("metode");
            $table->string("materi");
            $table->string("mapel");
            $table->string("kelas_lain")->nullable();
            $table->string("mapel_lain")->nullable();
            $table->timestamp("masuk")->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp("keluar")->nullable();
            $table->integer("status")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absen');
    }
}
