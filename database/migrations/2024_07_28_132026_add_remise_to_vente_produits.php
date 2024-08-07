<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemiseToVenteProduits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vente_produits', function (Blueprint $table) {
            $table->integer("remise")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vente_produits', function (Blueprint $table) {
            //
            $table->dropColumn('remise');
        });
    }
}
