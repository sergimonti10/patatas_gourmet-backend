<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->decimal('price', 10, 2);
            $table->decimal('weight', 10, 2);
            $table->longText('image'); //longBlob en la bbdd
            $table->longText('image2'); //longBlob en la bbdd
            $table->unsignedBigInteger('id_cut')->nullable();
            $table->foreign('id_cut')->references('id')->on('cuts')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'id_cut')) {
                $table->dropForeign(['id_cut']);
                $table->dropColumn('id_cut');
            }
        });
        Schema::dropIfExists('products');
    }
};
