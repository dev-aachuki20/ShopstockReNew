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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->unsigned()->index();
            $table->decimal('shipping_amount', 15, 2)->defautl(null)->nullable();
            $table->decimal('total_amount', 15, 2);
			$table->string('invoice_number')->nullable();
			$table->integer('area_id')->unsigned()->nullable();
			$table->date('invoice_date')->nullable();
			$table->date('due_date')->nullable();
            $table->enum('order_type', array('create', 'return'))->default('create');
			$table->tinyInteger('is_draft')->default(0);
			$table->tinyInteger('is_add_shipping')->default(0);
			$table->string('remark')->nullable();
			$table->string('sold_by')->nullable();
            $table->tinyInteger('is_modified')->default(0)->comment('1=> modified, 0=>not_modified');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
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
        Schema::dropIfExists('orders');
    }
};
