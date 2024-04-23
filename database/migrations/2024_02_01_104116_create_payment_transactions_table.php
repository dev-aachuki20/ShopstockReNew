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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->unsigned()->index();
            $table->enum('payment_type', array('credit', 'debit'))->default('credit');
            $table->enum('payment_way', array('order_create', 'order_return', 'by_split','by_cash', 'by_check', 'by_account'))->default('order_create');
			$table->string('voucher_number')->default(null)->nullable();
            $table->integer('order_id')->nullable();
            $table->string('extra_details')->nullable();
            $table->string('remark')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('entry_date')->nullable();
            $table->tinyInteger('is_modified')->default(0)->comment('1=> modified, 0=>not_modified');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('is_split')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
