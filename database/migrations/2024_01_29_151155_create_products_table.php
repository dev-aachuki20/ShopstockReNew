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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');           
            $table->string('name');
			$table->integer('group_id')->unsigned();
			$table->integer('sub_group_id')->unsigned();
			$table->integer('calculation_type')->unsigned(); 
			$table->integer('unit_type')->unsigned()->nullable();
            $table->decimal('price',10,2);
            $table->decimal('min_sale_price',10,2);
			$table->decimal('wholesaler_price',10,2);
			$table->decimal('retailer_price',10,2);
            $table->string('image')->nullable();
			$table->tinyInteger('is_height')->default(0);  
			$table->tinyInteger('is_width')->default(0);  
			$table->tinyInteger('is_length')->default(0); 
            $table->tinyInteger('is_sub_product')->default(0); 
			$table->tinyInteger('is_active')->default(0); 
            $table->string('extra_option_hint',50)->nullable();
            $table->integer('created_by')->unsigned()->default(0);
            $table->integer('updated_by')->unsigned()->default(0); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
