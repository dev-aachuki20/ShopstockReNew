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
        Schema::create('log_activities', function (Blueprint $table) {
            $table->bigIncrements('id');           
            $table->integer('user_id')->nullable();            
            $table->string('model_name');
            $table->string('activity');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->string('ip')->nullable();
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->string('agent')->nullable();
            $table->integer('created_by')->default(0)->unsigned();
            $table->integer('updated_by')->default(0)->unsigned();   				
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};
