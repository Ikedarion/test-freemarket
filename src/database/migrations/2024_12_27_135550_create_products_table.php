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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('brand_name');
            $table->text('description');
            $table->string('image');
            $table->enum('condition',['良好','目立った傷や汚れなし','やや傷や汚れあり','状態が悪い']);
            $table->enum('status', ['販売中','取引中','売却済み','取り下げ'])->default('販売中');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('set null');
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
        Schema::dropIfExists('products');
    }
};
