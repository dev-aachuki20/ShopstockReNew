<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_history', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->unsigned()->index();
            $table->integer('order_product_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->integer('height')->nullable();
			$table->integer('width')->nullable();
			$table->integer('length')->nullable();
            $table->decimal('total_price', 15, 2);
			$table->tinyInteger('is_draft')->default(0);
            $table->text('description')->default(null)->nullable();
            $table->json('other_details')->default(null)->nullable();
            $table->text('is_sub_product')->nullable();
            $table->datetime('order_update_time');
            $table->enum('update_status', ['add', 'update', 'delete', 'other'])->default('other');
            $table->json('order_data')->default(null)->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('deleted_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_history');
    }
};
